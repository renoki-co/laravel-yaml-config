<?php

namespace RenokiCo\LaravelYamlConfig;

use Illuminate\Support\ServiceProvider;

class LaravelYamlConfigServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/yaml-config.php' => config_path('yaml-config.php'),
        ], 'yaml-config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/yaml-config.php', 'yaml-config'
        );

        $this->loadConfigFile();
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

    /**
     * Load the config file.
     *
     * @return void
     */
    protected function loadConfigFile(): void
    {
        $config = @yaml_parse(
            @file_get_contents($this->app['config']['yaml-config.location'] ?? '')
        );

        if (! $config) {
            return;
        }

        $this->app['config']->set($config);
    }
}
