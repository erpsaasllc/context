<?php

use Illuminate\Support\Facades\Route;
use ERPSAAS\Context\Http\Controllers\CurrentCompanyController;
use ERPSAAS\Context\Http\Controllers\Livewire\ApiTokenController;
use ERPSAAS\Context\Http\Controllers\Livewire\PrivacyPolicyController;
use ERPSAAS\Context\Http\Controllers\Livewire\CompanyController;
use ERPSAAS\Context\Http\Controllers\Livewire\TermsOfServiceController;
use ERPSAAS\Context\Http\Controllers\Livewire\UserProfileController;
use ERPSAAS\Context\Http\Controllers\CompanyInvitationController;
use ERPSAAS\Context\Context;

Route::group(['middleware' => config('context.middleware', ['web'])], function () {
    if (Context::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terms-of-service', [TermsOfServiceController::class, 'show'])->name('terms.show');
        Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
    }

    $authMiddleware = config('context.guard')
            ? 'auth:'.config('context.guard')
            : 'auth';

    $authSessionMiddleware = config('context.auth_session', false)
            ? config('context.auth_session')
            : null;

    Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
        // User & Profile...
        Route::get('/user/profile', [UserProfileController::class, 'show'])->name('profile.show');

        Route::group(['middleware' => 'verified'], function () {
            // API...
            if (Context::hasApiFeatures()) {
                Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
            }

            // Companies...
            if (Context::hasCompanyFeatures()) {
                Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
                Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
                Route::put('/current-company', [CurrentCompanyController::class, 'update'])->name('current-company.update');

                Route::get('/company-invitations/{invitation}', [CompanyInvitationController::class, 'accept'])
                            ->middleware(['signed'])
                            ->name('company-invitations.accept');
            }
        });
    });
});
