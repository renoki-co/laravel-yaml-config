<?php

namespace RenokiCo\LaravelYamlConfig\Test;

use Illuminate\Support\Arr;
use RenokiCo\LaravelYamlConfig\LaravelYamlConfigServiceProvider;

class ConfigWithoutServiceProviderTest extends TestCase
{
    public function setUp(): void
    {
        @unlink(__DIR__.'/../vendor/orchestra/testbench-core/laravel/bootstrap/cache/config.php');

        parent::setUp();

        $this->artisan('cache:clear');

        foreach ($this->configLocations() as $location) {
            @unlink($location['path']);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->configLocations() as $location) {
            @unlink($location['path']);
        }

        parent::tearDown();

        @unlink(__DIR__.'/../vendor/orchestra/testbench-core/laravel/bootstrap/cache/config.php');
    }

    public function test_config_at_boot()
    {
        $originalApp = $this->app['config']->get('app');
        $originalDatabaseConnections = $this->app['config']->get('database.connections');
        $originalCustomConfig = $this->app['config']->get('custom');

        file_put_contents(
            base_path('.laravel.yaml'),
            <<<'YAML'
            app:
              name: Testing YAML
              timezone: Europe/Bucharest
            database:
              connections:
                mysql:
                  engine: custom_deep_engine
            custom:
              exists: true
              depths:
                - meters: 10
                  is_deep: false
                - meters: 10000
                  is_deep: true
              abyss:
                superabyss:
                  is_deepest: true
            YAML,
        );

        (new LaravelYamlConfigServiceProvider($this->app))->boot();

        $this->assertSame(array_merge($originalApp, [
            'name' => 'Testing YAML',
            'timezone' => 'Europe/Bucharest',
        ]), $this->app['config']->get('app'));

        $this->assertNotSame(
            $originalDatabaseConnections,
            $this->app['config']->get('database.connections'),
        );

        $swappedOriginalDatabaseConnections = $originalDatabaseConnections;
        Arr::set($swappedOriginalDatabaseConnections, 'mysql.engine', 'custom_deep_engine');

        $this->assertSame(
            $swappedOriginalDatabaseConnections,
            $this->app['config']->get('database.connections'),
        );

        $this->assertNotSame(
            $originalCustomConfig,
            $this->app['config']->get('custom'),
        );

        $this->assertSame([
            'exists' => true,
            'depths' => [
                ['meters' => 10, 'is_deep' => false],
                ['meters' => 10_000, 'is_deep' => true],
            ],
            'abyss' => [
                'superabyss' => [
                    'is_deepest' => true,
                ],
            ],
        ], $this->app['config']->get('custom'));
    }

    public function test_default_configs()
    {
        file_put_contents(
            base_path('.laravel.defaults.yaml'),
            <<<'YAML'
            custom:
              clusters: []
            YAML,
        );

        file_put_contents(
            base_path('.laravel.yaml'),
            <<<'YAML'
            custom:
              clusters:
                - region: us-east-1
                - region: eu-west-1
            YAML,
        );

        (new LaravelYamlConfigServiceProvider($this->app))->boot();

        $this->assertSame([
            'clusters' => [['region' => 'us-east-1'], ['region' => 'eu-west-1']],
        ], $this->app['config']->get('custom'));
    }

    public function test_default_gets_overwritten_by_sequential_objects()
    {
        file_put_contents(
            base_path('.laravel.defaults.yaml'),
            <<<'YAML'
            custom:
              clusters:
                - region: us-east-1
                - region: eu-west-1
            YAML,
        );

        file_put_contents(
            base_path('.laravel.yaml'),
            <<<'YAML'
            custom:
              clusters:
                - region: ap-south-1
            YAML,
        );

        (new LaravelYamlConfigServiceProvider($this->app))->boot();

        $this->assertSame([
            'clusters' => [['region' => 'ap-south-1'], ['region' => 'eu-west-1']],
        ], $this->app['config']->get('custom'));
    }
}
