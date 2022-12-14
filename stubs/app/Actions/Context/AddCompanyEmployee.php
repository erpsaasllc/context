<?php

namespace App\Actions\Context;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use ERPSAAS\Context\Contracts\AddsCompanyEmployees;
use ERPSAAS\Context\Events\AddingCompanyEmployee;
use ERPSAAS\Context\Events\CompanyEmployeeAdded;
use ERPSAAS\Context\Context;
use ERPSAAS\Context\Rules\Role;

class AddCompanyEmployee implements AddsCompanyEmployees
{
    /**
     * Add a new company employee to the given company.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    public function add($user, $company, string $email, string $role = null)
    {
        Gate::forUser($user)->authorize('addCompanyEmployee', $company);

        $this->validate($company, $email, $role);

        $newCompanyEmployee = Context::findUserByEmailOrFail($email);

        AddingCompanyEmployee::dispatch($company, $newCompanyEmployee);

        $company->users()->attach(
            $newCompanyEmployee, ['role' => $role]
        );

        CompanyEmployeeAdded::dispatch($company, $newCompanyEmployee);
    }

    /**
     * Validate the add employee operation.
     *
     * @param  mixed  $company
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    protected function validate($company, string $email, ?string $role)
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnCompany($company, $email)
        )->validateWithBag('addCompanyEmployee');
    }

    /**
     * Get the validation rules for adding a company employee.
     *
     * @return array
     */
    protected function rules()
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
            'role' => Context::hasRoles()
                            ? ['required', 'string', new Role]
                            : null,
        ]);
    }

    /**
     * Ensure that the user is not already on the company.
     *
     * @param  mixed  $company
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnCompany($company, string $email)
    {
        return function ($validator) use ($company, $email) {
            $validator->errors()->addIf(
                $company->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the company.')
            );
        };
    }
}
