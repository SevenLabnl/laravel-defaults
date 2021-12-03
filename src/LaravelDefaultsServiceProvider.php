<?php

namespace SevenLab\LaravelDefaults;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use SevenLab\LaravelDefaults\Http\Middleware\LogAfterRequest;

class LaravelDefaultsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param Kernel $kernel
     * @return void
     */
    public function boot(Kernel $kernel): void
    {
//        Add the LogAfterRequest middleware to the kernel, to be sure it will be used at every request
        if (config('laravel-defaults.log_after_request.enabled')) {
            $kernel->pushMiddleware(LogAfterRequest::class);
        }

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-defaults.php', 'laravel-defaults');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-defaults'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-defaults.php' => config_path('laravel-defaults.php'),
        ], 'laravel-defaults.config');
    }
}
