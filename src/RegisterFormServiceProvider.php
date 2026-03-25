<?php

namespace Eventat\RegisterForm;

use Eventat\RegisterForm\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class RegisterFormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->commands([
            InstallCommand::class,
        ]);
    }
}
