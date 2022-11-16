<?php

namespace RenokiCo\LaravelYamlConfig;

use Illuminate\Support\Arr;
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

        $this->loadConfigFromFiles();
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
     * Load the config files in order.
     *
     * @return void
     */
    protected function loadConfigFromFiles(): void
    {
        $locations = $this->app['config']['yaml-config.locations'] ?? '';

        foreach ($locations as $location) {
            $path = $location['path'] ?? '';

            if (! $path || ! file_exists($path)) {
                continue;
            }

            $config = @yaml_parse(@file_get_contents($path));

            if (! $config) {
                continue;
            }

            $configKeys = Arr::dot($config);

            foreach ($configKeys as $key => $value) {
                $this->app['config']->set($key, $value);
            }
        }
    }
}
