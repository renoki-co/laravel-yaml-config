Laravel YAML Config
===================

![CI](https://github.com/renoki-co/laravel-yaml-config/workflows/CI/badge.svg?branch=master)
[![codecov](https://codecov.io/gh/renoki-co/laravel-yaml-config/branch/master/graph/badge.svg)](https://codecov.io/gh/renoki-co/laravel-yaml-config/branch/master)
[![StyleCI](https://github.styleci.io/repos/506898995/shield?branch=master)](https://github.styleci.io/repos/506898995)
[![Latest Stable Version](https://poser.pugx.org/renoki-co/laravel-yaml-config/v/stable)](https://packagist.org/packages/renoki-co/laravel-yaml-config)
[![Total Downloads](https://poser.pugx.org/renoki-co/laravel-yaml-config/downloads)](https://packagist.org/packages/renoki-co/laravel-yaml-config)
[![Monthly Downloads](https://poser.pugx.org/renoki-co/laravel-yaml-config/d/monthly)](https://packagist.org/packages/renoki-co/laravel-yaml-config)
[![License](https://poser.pugx.org/renoki-co/laravel-yaml-config/license)](https://packagist.org/packages/renoki-co/laravel-yaml-config)

The usual Laravel config files, but with one YAML file. Write objects and arrays in your config without having to write ugly inline, JSON.

## 🚀 Installation

You can install the package via composer:

```bash
composer require renoki-co/laravel-yaml-config
```

Publish the config:

```bash
php artisan vendor:publish --provider="RenokiCo\LaravelYamlConfig\LaravelYamlConfigServiceProvider" --tag="config"
```

## 🙌 Usage

This package makes sure you don't have to use inline-JSON in your .env files that would look like this:

```php
AWS_CLUSTERS='[{"region": "us-east-1", "url": "..."}, {"region": "eu-west-1", "url": "..."}]'

// config/clusters.php
return [
    'aws' => env('AWS_CLUSTERS', json_encode([
        // create a default for it
    ])),
];
```

First, create a local `.laravel.yaml` file in your root Laravel project:

```bash
touch .laravel.yaml
```

Declare your configuration in YAML:

```yaml
clusters:
  aws:
    - region: us-east-1
      url: https://...
    - region: eu-west-1
      url: https://...
  google:
    - region: europe-west1
      url: https://...
```

```php
foreach (config('clusters.aws') as $cluster) {
    // $cluster['region']
}
```

You shouldn't commit your `.laravel.yaml` files to your code repo:

```bash
echo ".laravel.yaml\n.laravel.yml" >> .gitignore
```

## Replacing nested variables

While the package lets you set arbitrary config without messing with ugly encoded JSON, you can still use it to update nested variables with already-existing configuration:

```yaml
database:
  connectons:
    mysql:
      host: mysql
clusters:
  aws:
    # ...
```

## Declaring defaults

While you shouldn't commit your `.laravel.yaml` file, you can commit a `.laravel.defaults.yaml` file that can contain defaults for specific configs you have declared:

```bash
touch .laravel.config.yaml
```

```yaml
clusters:
  aws: []
  google: []
```

### Sequential lists

Take extra caution when declaring defaults for lists of items:

```yaml
# .laravel.defaults.yaml
clusters:
  - region: us-east-1
  - region: eu-west-1
```

When a config that contains lists that are pre-filled, with a `.laravel.yaml` like this, an odd behavior appears:

```yaml
# .laravel.yaml
clusters:
  - region: ap-south-1
```

When you'd expect the final value of `clusters` to contain only one item, it will actually contain two items, with the first one being replaced instead:

```php
// 'clusters' => [
//     ['region' => 'ap-south-1'],
//     ['region' => 'eu-west-1'],
// ]

dump(config('clusters'));
```

## 🐛 Testing

``` bash
vendor/bin/phpunit
```

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 🔒  Security

If you discover any security related issues, please email alex@renoki.org instead of using the issue tracker.

## 🎉 Credits

- [Alex Renoki](https://github.com/rennokki)
- [All Contributors](../../contributors)
