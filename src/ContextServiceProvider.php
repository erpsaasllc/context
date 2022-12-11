<?php

namespace ERPSAAS\Context;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Laravel\Fortify\Fortify;
use ERPSAAS\Context\Http\Livewire\ApiTokenManager;
use ERPSAAS\Context\Http\Livewire\CreateCompanyForm;
use ERPSAAS\Context\Http\Livewire\DeleteCompanyForm;
use ERPSAAS\Context\Http\Livewire\DeleteUserForm;
use ERPSAAS\Context\Http\Livewire\LogoutOtherBrowserSessionsForm;
use ERPSAAS\Context\Http\Livewire\NavigationMenu;
use ERPSAAS\Context\Http\Livewire\CompanyEmployeeManager;
use ERPSAAS\Context\Http\Livewire\TwoFactorAuthenticationForm;
use ERPSAAS\Context\Http\Livewire\UpdatePasswordForm;
use ERPSAAS\Context\Http\Livewire\UpdateProfileInformationForm;
use ERPSAAS\Context\Http\Livewire\UpdateCompanyNameForm;
use Livewire\Livewire;

class ContextServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/context.php', 'context');

        $this->app->afterResolving(BladeCompiler::class, function () {
            if (config('context.stack') === 'filament' && class_exists(Livewire::class)) {
                Livewire::component('navigation-menu', NavigationMenu::class);
                Livewire::component('profile.update-profile-information-form', UpdateProfileInformationForm::class);
                Livewire::component('profile.update-password-form', UpdatePasswordForm::class);
                Livewire::component('profile.two-factor-authentication-form', TwoFactorAuthenticationForm::class);
                Livewire::component('profile.logout-other-browser-sessions-form', LogoutOtherBrowserSessionsForm::class);
                Livewire::component('profile.delete-user-form', DeleteUserForm::class);

                if (Features::hasApiFeatures()) {
                    Livewire::component('api.api-token-manager', ApiTokenManager::class);
                }

                if (Features::hasCompanyFeatures()) {
                    Livewire::component('companies.create-company-form', CreateCompanyForm::class);
                    Livewire::component('companies.update-company-name-form', UpdateCompanyNameForm::class);
                    Livewire::component('companies.company-employee-manager', CompanyEmployeeManager::class);
                    Livewire::component('companies.delete-company-form', DeleteCompanyForm::class);
                }
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'context');

        Fortify::viewPrefix('auth.');

        $this->configureComponents();
        $this->configurePublishing();
        $this->configureRoutes();
        $this->configureCommands();

        RedirectResponse::macro('banner', function ($message) {
            return $this->with('flash', [
                'bannerStyle' => 'success',
                'banner' => $message,
            ]);
        });

        RedirectResponse::macro('dangerBanner', function ($message) {
            return $this->with('flash', [
                'bannerStyle' => 'danger',
                'banner' => $message,
            ]);
        });

        //if (config('context.stack') === 'inertia') {
            //$this->bootInertia();
        //}
    }

    /**
     * Configure the Company Blade components.
     *
     * @return void
     */
    protected function configureComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('action-message');
            $this->registerComponent('action-section');
            $this->registerComponent('application-logo');
            $this->registerComponent('application-mark');
            $this->registerComponent('authentication-card');
            $this->registerComponent('authentication-card-logo');
            $this->registerComponent('banner');
            $this->registerComponent('button');
            $this->registerComponent('confirmation-modal');
            $this->registerComponent('confirms-password');
            $this->registerComponent('danger-button');
            $this->registerComponent('dialog-modal');
            $this->registerComponent('dropdown');
            $this->registerComponent('dropdown-link');
            $this->registerComponent('form-section');
            $this->registerComponent('input');
            $this->registerComponent('checkbox');
            $this->registerComponent('input-error');
            $this->registerComponent('label');
            $this->registerComponent('modal');
            $this->registerComponent('nav-link');
            $this->registerComponent('responsive-nav-link');
            $this->registerComponent('responsive-switchable-company');
            $this->registerComponent('secondary-button');
            $this->registerComponent('section-border');
            $this->registerComponent('section-title');
            $this->registerComponent('switchable-company');
            $this->registerComponent('validation-errors');
            $this->registerComponent('welcome');
        });
    }

    /**
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function registerComponent(string $component)
    {
        Blade::component('context::components.'.$component, 'context-'.$component);
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../stubs/config/context.php' => config_path('context.php'),
        ], 'context-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/context'),
        ], 'context-views');

        $this->publishes([
            __DIR__.'/../database/migrations/2014_10_12_000000_create_users_table.php' => database_path('migrations/2014_10_12_000000_create_users_table.php'),
        ], 'context-migrations');

        $this->publishes([
            __DIR__.'/../database/migrations/2020_05_21_100000_create_companies_table.php' => database_path('migrations/2020_05_21_100000_create_companies_table.php'),
            __DIR__.'/../database/migrations/2020_05_21_200000_create_company_user_table.php' => database_path('migrations/2020_05_21_200000_create_company_user_table.php'),
            __DIR__.'/../database/migrations/2020_05_21_300000_create_company_invitations_table.php' => database_path('migrations/2020_05_21_300000_create_company_invitations_table.php'),
        ], 'context-company-migrations');

        $this->publishes([
            __DIR__.'/../routes/'.config('context.stack').'.php' => base_path('routes/context.php'),
        ], 'context-routes');
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        if (Context::$registersRoutes) {
            Route::group([
                'namespace' => 'ERPSAAS\Context\Http\Controllers',
                'domain' => config('filament.domain'),
                'middleware' => config('filament.middleware.base', 'web'),
                'name' => ('filament.'),
                'prefix' => config('filament.path'),
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/'.config('context.stack').'.php');
            });
        }
    }

    /**
     * Configure the commands offered by the application.
     *
     * @return void
     */
    protected function configureCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }
}
