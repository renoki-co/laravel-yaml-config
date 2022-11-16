<?php

namespace RenokiCo\LaravelYamlConfig\Test;

use Illuminate\Support\Arr;
use RenokiCo\LaravelYamlConfig\LaravelYamlConfigServiceProvider;

class ConfigWithServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        @unlink(__DIR__.'/../vendor/orchestra/testbench-core/laravel/bootstrap/cache/config.php');

        return [
            LaravelYamlConfigServiceProvider::class,
        ];
    }

    public function setUp(): void
    {
        @unlink(__DIR__.'/../vendor/orchestra/testbench-core/laravel/bootstrap/cache/config.php');

        parent::setUp();

        $this->artisan('cache:clear');

        foreach ($this->app['config']['yaml-config.locations'] as $location) {
            @unlink($location['path']);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->app['config']['yaml-config.locations'] as $location) {
            @unlink($location['path']);
        }

        parent::tearDown();

        @unlink(__DIR__.'/../vendor/orchestra/testbench-core/laravel/bootstrap/cache/config.php');
    }

    public function test_cache_config()
    {
        $this->refreshApplication();

        $originalMailgun = $this->app['config']->get('services.mailgun');
        $originalDatabaseConnections = $this->app['config']->get('database.connections');
        $originalCustomConfig = $this->app['config']->get('custom');

        file_put_contents(
            base_path('.laravel.yaml'),
            <<<'YAML'
            services:
              mailgun:
                domain: test.local
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

        // No need to call the provider's boot() because config:cache will do it.
        $this->artisan('config:cache');
        $this->refreshApplication();

        $this->assertSame(array_merge($originalMailgun, [
            'domain' => 'test.local',
        ]), $this->app['config']->get('services.mailgun'));

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
}
