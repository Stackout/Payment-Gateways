<?php


return [

    /**
     * This config setting let's us know that we are in development mode. You can switch between production and development 
     * by simply setting this variable.
     * 
     * (i.e.) Stripe has 'test' keys you can use for testing your payments through their test APIs while you develop your
     * front end.
     */
    'develpoment' => true,




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

    'Stripe' => [

        /**
         * Development Settings.
         */
        'development' => 
        [
            'public' => env('STRIPE_PUBLIC_KEY_TEST', ''),
            'private' => env('STRIPE_PRIVATE_KEY_TEST', '')
        ],
        'production' => 
        [
            'public' => env(),
            'private' => env(),
        ]
        
    ],


    
];