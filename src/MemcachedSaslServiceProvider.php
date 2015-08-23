<?php

namespace Ripples\Memcached;

use Illuminate\Support\ServiceProvider;

class MemcachedSaslServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('memcached_sasl.connector', function ($app) {
            return new MemcachedSaslConnector();
        });
    }

    public function boot()
    {
        $this->app['cache']->extend('memcached_sasl', function ($app, $config) {
            $memcached = $app['memcached_sasl.connector']->connect($config['servers'], $config['auth']);

            $prefix = $app['config']['cache.prefix'];
            $store = new \Illuminate\Cache\MemcachedStore($memcached, $prefix);
            return new \Illuminate\Cache\Repository($store);
        });
    }

}
