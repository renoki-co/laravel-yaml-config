Package Name Here
===================================

![CI](https://github.com/renoki-co/laravel-yaml-env/workflows/CI/badge.svg?branch=master)
[![codecov](https://codecov.io/gh/renoki-co/laravel-yaml-env/branch/master/graph/badge.svg)](https://codecov.io/gh/renoki-co/laravel-yaml-env/branch/master)
[![StyleCI](https://github.styleci.io/repos/:styleci_code/shield?branch=master)](https://github.styleci.io/repos/:styleci_code)
[![Latest Stable Version](https://poser.pugx.org/renoki-co/laravel-yaml-env/v/stable)](https://packagist.org/packages/renoki-co/laravel-yaml-env)
[![Total Downloads](https://poser.pugx.org/renoki-co/laravel-yaml-env/downloads)](https://packagist.org/packages/renoki-co/laravel-yaml-env)
[![Monthly Downloads](https://poser.pugx.org/renoki-co/laravel-yaml-env/d/monthly)](https://packagist.org/packages/renoki-co/laravel-yaml-env)
[![License](https://poser.pugx.org/renoki-co/laravel-yaml-env/license)](https://packagist.org/packages/renoki-co/laravel-yaml-env)

The usual Laravel .env file, but with YAML. Write objects and arrays in your config without having to write inline, ugly JSON.

## 🚀 Installation

You can install the package via composer:

```bash
composer require renoki-co/laravel-yaml-env
```

Publish the config:

```bash
$ php artisan vendor:publish --provider="RenokiCo\LaravelYamlEnv\LaravelYamlEnvServiceProvider" --tag="config"
```

## 🙌 Usage

```php
$ //
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
