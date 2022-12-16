<?php

namespace App\Providers;

use App\Actions\Context\AddCompanyEmployee;
use App\Actions\Context\CreateCompany;
use App\Actions\Context\DeleteCompany;
use App\Actions\Context\DeleteUser;
use App\Actions\Context\InviteCompanyEmployee;
use App\Actions\Context\RemoveCompanyEmployee;
use App\Actions\Context\UpdateCompanyName;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use App\Filament\Pages\User\Profile;
use App\Filament\Pages\Company\Settings;
use ERPSAAS\Context\Context;

class ContextServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('context.enable_profile_page') && config('context.show_profile_page_in_user_menu')) {
            Filament::serving(function () {
                Filament::registerUserMenuItems([
                    'account' => UserMenuItem::make()->url(Profile::getUrl()),
                ]);
            });
        }

        if (config('context.enable_company_settings_page') && config('context.show_company_settings_page_in_user_menu')) {
            Filament::serving(function () {
                Filament::registerUserMenuItems([
                    'settings' => UserMenuItem::make()->label('Settings')->url(Settings::getUrl())->icon('heroicon-s-cog'),
                ]);
            });
        }

        $this->app->singleton(
            \Laravel\Fortify\Contracts\LogoutResponse::class,
            \ERPSAAS\Context\Http\Responses\LogoutResponse::class,
        );
        $this->configurePermissions();

        Context::createCompaniesUsing(CreateCompany::class);
        Context::updateCompanyNamesUsing(UpdateCompanyName::class);
        Context::addCompanyEmployeesUsing(AddCompanyEmployee::class);
        Context::inviteCompanyEmployeesUsing(InviteCompanyEmployee::class);
        Context::removeCompanyEmployeesUsing(RemoveCompanyEmployee::class);
        Context::deleteCompaniesUsing(DeleteCompany::class);
        Context::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Context::defaultApiTokenPermissions(['read']);

        Context::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Administrator users can perform any action.');

        Context::role('editor', 'Editor', [
            'read',
            'create',
            'update',
        ])->description('Editor users have the ability to read, create, and update.');
    }
}
