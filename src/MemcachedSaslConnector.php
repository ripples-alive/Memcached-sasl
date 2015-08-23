<?php

namespace Ripples\Memcached;

use Memcached;
use RuntimeException;

class MemcachedSaslConnector extends \Illuminate\Cache\MemcachedConnector
{

    /**
     * Create a new Memcached SASL connection.
     *
     * @param  array $servers
     * @param  [array] $auth
     *
     * @return \Memcached
     *
     * @throws \RuntimeException
     */
    public function connect(array $servers, array $auth = [])
    {
        $memcached = $this->getMemcached();

        // Turn off compression.
        $memcached->setOption(Memcached::OPT_COMPRESSION, false);
        // Use binary protocol.
        $memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, true);

        // For each server in the array, we'll just extract the configuration and add
        // the server to the Memcached connection. Once we have added all of these
        // servers we'll verify the connection is successful and return it back.
        foreach ($servers as $server) {
            $memcached->addServer(
                $server['host'], $server['port'], $server['weight']
            );
        }

        if (!empty($auth)) {
            // If require auth, assert memcached sasl is supported.
            if (!ini_get('memcached.use_sasl')) {
                throw new RuntimeException('Memcached SASL should be supported.');
            }
            $memcached->setSaslAuthData($auth['user'], $auth['password']);
        }

        $memcachedStatus = $memcached->getVersion();

        if (!is_array($memcachedStatus)) {
            throw new RuntimeException('No Memcached servers added.');
        }

        if (in_array('255.255.255', $memcachedStatus) && count(array_unique($memcachedStatus)) === 1) {
            throw new RuntimeException('Could not establish Memcached connection.');
        }

        return $memcached;
    }

} 