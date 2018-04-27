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
     * Generate a new application gateway defuse key
     * 
     * Open terminal, navigate to your project folder.
     * 
     * To create a defuse key execute from terminal the following command:
     * 
     * $ vendor/bin/generate-defuse-key
     * 
     * It is imperative for security concerns that you CHANGE this key and store it in the .env file.
     */
    'defuse_key' => env('GATEWAY_DEFEUSE_KEY', 'def000001248979ce1096178ba91f620fecaa36f1e2c860981768ea07db64c7210d758232d29a58bef39f0ff6c7afb11f030623bd2174ba2689524fecdc11649df8e5a83'),

    /**
     * Set the default gateway
     * 
     * Default gatewy is stripe
     */

    'default' => 'stripe',

    /**
     * Allow chargeable interruptions
     * 
     * This flag allows us to do some logic before we charge the card. Setting this to false disables interruptions.
     * 
     * This allows is to either immediatly capture a charge or not. If we immeditly capture a charge, you can do some 
     * logic before capturing the payment.
     * 
     */

    'interruptible' => true,

    /**
     * Set the settings table (or collection NoSQL) you want to use to store your keys.
     */

    'settings_table' => env('GATEWAY_SETTINGS_TABLE', 'gateway_settings'),

    /**
     * Set the settings table (or collection NoSQL) you want to use to store your keys.
     */

    'settings_model' => env('GATEWAY_SETTINGS_MODEL', \Stackout\PaymentGateways\GatewaySetting::class),

    /**
     * Allow the storing of private keys in your database
     * ------------------------------------------------------------------
     * 
     * All private keys will be encrypted with 
     */

    'store_private_keys' => true,

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
         * Set base stripe base default settings
         */
        'version' => env('STRIPE_VERSION', 'v3'),
        'endpoint' => env('STRIPE_ENDPOINT', 'https://api.stripe.com/v3'),

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


    'paypal' => [

        /**
         * Base Paypal Pro Settings
         */
        'version' => env('PAYPAL_VERSION', 'dev-2.0-beta'),
        'endpoint' => env('PAYPAL_ENDPOINT', ''),

        /**
         * paypal Pros's Sandbox Settings.
         */
        'development' => 
        [
            'username' => env('PAYPAL_SANDBOX_USERNAME'),
            'password' => env('PAYPAL_SANDBOX_PASSWORD'),
            'signature' => env('PAYPAL_SANDBOX_SIGNATURE', ''),
        ],
        /**
         * Paypal Pro's Production Settings.
         */
        'production' => 
        [
            'username' => env('PAYPAL_USERNAME'),
            'password' => env('PAYPAL_PASSWORD'),
            'signature' => env('PAYPAL_SIGNATURE'),
        ]

    ],
    
];