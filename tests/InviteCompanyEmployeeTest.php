<?php

namespace ERPSAAS\Context\Tests;

use App\Actions\Context\CreateCompany;
use App\Actions\Context\InviteCompanyEmployee;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use ERPSAAS\Context\Context;
use ERPSAAS\Context\Tests\Fixtures\CompanyPolicy;
use ERPSAAS\Context\Tests\Fixtures\User;

class InviteCompanyEmployeeTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);

        Context::useUserModel(User::class);
    }

    public function test_company_employees_can_be_invited()
    {
        Mail::fake();

        Context::role('admin', 'Admin', ['foo']);

        $this->migrate();

        $company = $this->createCompany();

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $action = new InviteCompanyEmployee;

        $action->invite($company->owner, $company, 'adam@laravel.com', 'admin');

        $company = $company->fresh();

        $this->assertCount(0, $company->users);
        $this->assertCount(1, $company->companyInvitations);
        $this->assertEquals('adam@laravel.com', $company->companyInvitations->first()->email);
        $this->assertEquals($company->id, $company->companyInvitations->first()->company->id);
    }

    public function test_user_cant_already_be_on_company()
    {
        Mail::fake();

        $this->expectException(ValidationException::class);

        $this->migrate();

        $company = $this->createCompany();

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $action = new InviteCompanyEmployee;

        $action->invite($company->owner, $company, 'adam@laravel.com', 'admin');
        $this->assertTrue(true);
        $action->invite($company->owner, $company->fresh(), 'adam@laravel.com', 'admin');
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
}
