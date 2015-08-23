# Memcached SASL extension for Laravel5

This is a custom cache extension of memcached sasl for laravel5, especially for aliyun ocs.

## Installation

This package can be installed through `composer`.

```bash
composer require ripples/memcached-sasl
```

## Usage

In order to use the extension, the service provider must be registered.

```php
// bootstrap/app.php
$app->register(Ripples\Memcached\MemcachedSaslServiceProvider::class);
```

Finally, add a store to you config file `cache.php` and update cache driver to `memcached_sasl`.

```php
return [
    'default' => 'memcached_sasl',

    'stores' => [
        'memcached_sasl' => [
            'driver' => 'memcached_sasl',
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100
                ]
            ],
            'auth' => [
                'username' => env('MEMCACHED_USERNAME', ''),
                'password' => env('MEMCACHED_PASSWORD', '')
            ]
        ],
    ]
]
```