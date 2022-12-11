<?php

namespace App\Actions\Context;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use ERPSAAS\Context\Contracts\CreatesCompanies;
use ERPSAAS\Context\Events\AddingCompany;
use ERPSAAS\Context\Context;

class CreateCompany implements CreatesCompanies
{
    /**
     * Validate and create a new company for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input)
    {
        Gate::forUser($user)->authorize('create', Context::newCompanyModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createCompany');

        AddingCompany::dispatch($user);

        $user->switchCompany($company = $user->ownedCompanies()->create([
            'name' => $input['name'],
            'personal_company' => false,
        ]));

        return $company;
    }
}
