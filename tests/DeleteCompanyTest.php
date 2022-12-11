<?php

namespace ERPSAAS\Context\Tests;

use App\Actions\Context\CreateCompany;
use App\Actions\Context\DeleteCompany;
use App\Models\Company;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use ERPSAAS\Context\Actions\ValidateCompanyDeletion;
use ERPSAAS\Context\Context;
use ERPSAAS\Context\Tests\Fixtures\CompanyPolicy;
use ERPSAAS\Context\Tests\Fixtures\User;

class DeleteCompanyTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);
        Context::useUserModel(User::class);
    }

    public function test_company_can_be_deleted()
    {
        $this->migrate();

        $company = $this->createCompany();

        $action = new DeleteCompany;

        $action->delete($company);

        $this->assertNull($company->fresh());
    }

    public function test_company_deletion_can_be_validated()
    {
        Context::useUserModel(User::class);

        $this->migrate();

        $company = $this->createCompany();

        $action = new ValidateCompanyDeletion;

        $action->validate($company->owner, $company);

        $this->assertTrue(true);
    }

    public function test_personal_company_cant_be_deleted()
    {
        $this->expectException(ValidationException::class);

        Context::useUserModel(User::class);

        $this->migrate();

        $company = $this->createCompany();

        $company->forceFill(['personal_company' => true])->save();

        $action = new ValidateCompanyDeletion;

        $action->validate($company->owner, $company);
    }

    public function test_non_owner_cant_delete_company()
    {
        $this->expectException(AuthorizationException::class);

        Context::useUserModel(User::class);

        $this->migrate();

        $company = $this->createCompany();

        $action = new ValidateCompanyDeletion;

        $action->validate(User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]), $company);
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
