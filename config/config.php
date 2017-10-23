<?php

return array(

    'staging' => [
        /**
         * Statsd host.
         */
        'host' => 'localhost',

        /**
         * Statsd port.
         */
        'port' => 8126,

        /**
         * Statsd protocol.
         */
        'protocol' => 'udp',

        /**
         * Environments in which we allow sending to Statsd. It has priority over production config
         */
        'environments' => ['local', 'dev']
    ],

    'production' => [
        /**
         * Statsd host.
         */
        'host' => 'localhost',

        /**
         * Statsd port.
         */
        'port' => 8126,

        /**
         * Statsd protocol.
         */
        'protocol' => 'udp',

        /**
         * Environments in which we allow sending to Statsd.
         */
        'environments' => ['prod', 'production'],

    ]
);
