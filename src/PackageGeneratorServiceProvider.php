<?php

namespace AhmetShen\PackageGenerator;

use Illuminate\Support\ServiceProvider;

class PackageGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration.
        $this->mergeConfigFrom(__DIR__.'/../config/package-generator.php', 'package-generator');

        // Register the main class to use with the facade.
        $this->app->singleton('package-generator', function () {
            return new PackageGenerator;
        });
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Registering package commands.
        $this->commands([
            Console\InstallCommand::class,
        ]);
    }
}
