<?php

namespace App\Actions\Context;

use Illuminate\Support\Facades\DB;
use ERPSAAS\Context\Contracts\DeletesCompanies;
use ERPSAAS\Context\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * The company deleter implementation.
     *
     * @var \ERPSAAS\Context\Contracts\DeletesCompanies
     */
    protected $deletesCompanies;

    /**
     * Create a new action instance.
     *
     * @param  \ERPSAAS\Context\Contracts\DeletesCompanies  $deletesCompanies
     * @return void
     */
    public function __construct(DeletesCompanies $deletesCompanies)
    {
        $this->deletesCompanies = $deletesCompanies;
    }

    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        DB::transaction(function () use ($user) {
            $this->deleteCompanies($user);
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
        });
    }

    /**
     * Delete the companies and company associations attached to the user.
     *
     * @param  mixed  $user
     * @return void
     */
    protected function deleteCompanies($user)
    {
        $user->companies()->detach();

        $user->ownedCompanies->each(function ($company) {
            $this->deletesCompanies->delete($company);
        });
    }
}
