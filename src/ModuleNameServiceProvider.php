<?php

namespace Eventat\ModuleName;

use Illuminate\Support\ServiceProvider;
use Eventat\ModuleName\Commands\InstallCommand;

class ModuleNameServiceProvider extends ServiceProvider
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