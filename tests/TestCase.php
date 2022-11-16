<?php

namespace RenokiCo\LaravelYamlConfig\Test;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            //
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'wslxrEFGWY6GfGhvN9L3wH3KSRJQQpBD');
    }

    protected function configLocations()
    {
        return [
            ['path' => base_path('.laravel.defaults.yaml')],
            ['path' => base_path('.laravel.defaults.yml')],
            ['path' => base_path('.laravel.yaml')],
            ['path' => base_path('.laravel.yml')],
        ];
    }
}
