<?php

namespace App\Providers;

use App\Actions\Context\DeleteUser;
use Illuminate\Support\ServiceProvider;
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
        $this->configurePermissions();

        Context::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Context::defaultApiTokenPermissions(['read']);

        Context::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
