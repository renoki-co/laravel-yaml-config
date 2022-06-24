<?php

namespace RenokiCo\LaravelYamlEnv;

use Illuminate\Support\ServiceProvider;

class LaravelYamlEnvServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/yaml-env.php' => config_path('yaml-env.php'),
        ], 'yaml-env');

        $this->mergeConfigFrom(
            __DIR__.'/../config/yaml-env.php', 'yaml-env'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
