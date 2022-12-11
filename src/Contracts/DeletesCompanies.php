<?php

namespace ERPSAAS\Context\Contracts;

interface DeletesCompanies
{
    /**
     * Delete the given company.
     *
     * @param  mixed  $company
     * @return void
     */
    public function delete($company);
}
