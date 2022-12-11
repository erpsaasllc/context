<?php

namespace ERPSAAS\Context\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ERPSAAS\Context\Context;

class CurrentCompanyController extends Controller
{
    /**
     * Update the authenticated user's current company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $company = Context::newCompanyModel()->findOrFail($request->company_id);

        if (! $request->user()->switchCompany($company)) {
            abort(403);
        }

        return redirect(config('fortify.home'), 303);
    }
}
