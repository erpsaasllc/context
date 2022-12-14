<?php

namespace ERPSAAS\Context\Tests;

use App\Actions\Context\CreateCompany;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use ERPSAAS\Context\Context;
use ERPSAAS\Context\Tests\Fixtures\CompanyPolicy;
use ERPSAAS\Context\Tests\Fixtures\User;

class CurrentCompanyControllerTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);
        Context::useUserModel(User::class);
    }

    public function test_can_switch_to_company_the_user_belongs_to()
    {
        $this->migrate();

        $action = new CreateCompany;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $company = $action->create($user, ['name' => 'Test Company']);

        $response = $this->actingAs($user)->put('/current-company', ['company_id' => $company->id]);

        $response->assertRedirect('/home');

        $this->assertEquals($company->id, $user->fresh()->currentCompany->id);
        $this->assertTrue($user->isCurrentCompany($company));
    }

    public function test_cant_switch_to_company_the_user_does_not_belong_to()
    {
        $this->migrate();

        $action = new CreateCompany;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $company = $action->create($user, ['name' => 'Test Company']);

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $response = $this->actingAs($otherUser)->put('/current-company', ['company_id' => $company->id]);

        $response->assertStatus(403);
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
