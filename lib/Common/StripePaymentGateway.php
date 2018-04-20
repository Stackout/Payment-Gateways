<?php


namespace Stackout\PaymentGateways;

use Stripe\Stripe;


class StripePaymentGateway extends Gateway{

    protected $stripeToken;

    public function __construct($stripeToken, $attributes = [], $publicKey = null, $privateKey = null){

        // Set the gateway name for this instance
        $this->gatewayName = Gateway::STRIPE_NAME;

        // Store values for the private and publish keys from the config file.
        $this->privateKey = \Config::get('payment_gateways.');
        $this->publicKey = \Config::get('');

        $this->attributes['responseToken'] = $stripeToken;



        parent::__construct($attributes, $privateKey, $publicKey);

    }

    public function riot(){

        echo"We are rioting in stripe!";
    }



}