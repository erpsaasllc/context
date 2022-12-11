<?php

namespace ERPSAAS\Context\Http\Controllers\Livewire;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use ERPSAAS\Context\Context;

class CompanyController extends Controller
{
    /**
     * Show the company management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $companyId
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $companyId)
    {
        $company = Context::newCompanyModel()->findOrFail($companyId);

        if (Gate::denies('view', $company)) {
            abort(403);
        }

        return view('companies.show', [
            'user' => $request->user(),
            'company' => $company,
        ]);
    }

    /**
     * Show the company creation screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Context::newCompanyModel());

        return view('companies.create', [
            'user' => $request->user(),
        ]);
    }
}
