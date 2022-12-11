<?php

namespace ERPSAAS\Context\Tests;

use App\Actions\Context\CreateCompany;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use ERPSAAS\Context\Contracts\AddsCompanyEmployees;
use ERPSAAS\Context\Context;
use ERPSAAS\Context\Tests\Fixtures\CompanyPolicy;
use ERPSAAS\Context\Tests\Fixtures\User;

class CompanyInvitationControllerTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);
        Context::useUserModel(User::class);
    }

    public function test_company_invitations_can_be_accepted()
    {
        $this->mock(AddsCompanyEmployees::class)->shouldReceive('add')->once();

        Context::role('admin', 'Admin', ['foo', 'bar']);
        Context::role('editor', 'Editor', ['baz', 'qux']);

        $this->migrate();

        $company = $this->createCompany();

        $invitation = $company->companyInvitations()->create(['email' => 'adam@laravel.com', 'role' => 'admin']);

        $url = URL::signedRoute('company-invitations.accept', ['invitation' => $invitation]);

        $response = $this->actingAs($company->owner)->get($url);

        $response->assertRedirect();
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
