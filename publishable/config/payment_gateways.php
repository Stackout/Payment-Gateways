<?php


return [

    /**
     * This config setting let's us know that we are in development mode. You can switch between production and development 
     * by simply setting this variable.
     * 
     * (i.e.) Stripe has 'test' keys you can use for testing your payments through their test APIs while you develop your
     * front end.
     */
    'development' => true,

    /**
     * Set up the cache system to use REDIS. We can cache the public keys and store them inside a redis server
     * 'cache' => 'redis'. 
     * 
     * Package will work really well with Redis or Memcache
     */

    'cache' => '',

    /**
     * Set the default gateway
     * 
     * Default gatewy is stripe
     */

    'default' => 'stripe',

    /**
     * These are the config variables used by the payment gateway package.
     * 
     * ------------------------------------------------------------------
     * 
     * TO IMPEMENT:
     * Stripe
     * Authorize.NET
     * Google Checkout
     */

    'stripe' => [

        /**
         * Stripe's Development Settings.
         */
        'development' => 
        [
            'public' => env('STRIPE_PUBLIC_KEY_TEST', ''),
            'private' => env('STRIPE_PRIVATE_KEY_TEST', '')
        ],
        /**
         * Stripe's 
         */
        'production' => 
        [
            'public' => env('STRIPE_PUBLIC_KEY', ''),
            'private' => env('STRIPE_PUBLIC_KEY', '')
        ]

    ],


    
];