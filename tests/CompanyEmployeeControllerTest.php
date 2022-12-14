<?php

namespace ERPSAAS\Context\Tests;

use App\Actions\Context\CreateCompany;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use ERPSAAS\Context\Context;
use ERPSAAS\Context\Tests\Fixtures\CompanyPolicy;
use ERPSAAS\Context\Tests\Fixtures\User;
use Laravel\Sanctum\TransientToken;

class CompanyEmployeeControllerTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);
        Context::useUserModel(User::class);
    }

    public function test_company_employee_permissions_can_be_updated()
    {
        Context::role('admin', 'Admin', ['foo', 'bar']);
        Context::role('editor', 'Editor', ['baz', 'qux']);

        $this->migrate();

        $company = $this->createCompany();

        $adam = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $company->users()->attach($adam, ['role' => 'admin']);

        $response = $this->actingAs($company->owner)->put('/companies/'.$company->id.'/employees/'.$adam->id, [
            'role' => 'editor',
        ]);

        $response->assertRedirect();

        $adam = $adam->fresh();

        $adam->withAccessToken(new TransientToken);

        $this->assertTrue($adam->hasCompanyPermission($company, 'baz'));
        $this->assertTrue($adam->hasCompanyPermission($company, 'qux'));
    }

    public function test_company_employee_permissions_cant_be_updated_if_not_authorized()
    {
        $this->migrate();

        $company = $this->createCompany();

        $adam = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $company->users()->attach($adam, ['role' => 'admin']);

        $response = $this->actingAs($adam)->put('/companies/'.$company->id.'/employees/'.$adam->id, [
            'role' => 'admin',
        ]);

        $response->assertStatus(403);
    }

    protected function createCompany()
    {
        $action = new CreateCompany;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        return $action->create($user, ['name' => 'Test Company']);
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('context.stack', 'filament');
        $app['config']->set('context.features', ['companies']);
    }
}
