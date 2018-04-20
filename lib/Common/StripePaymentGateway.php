<?php


namespace Stackout\PaymentGateways;

use Stripe\Stripe;


class StripePaymentGateway extends Gateway{

    protected $stripeToken;

    public function __construct($stripeToken, $attributes = []){

        $this->attributes['responseToken'] = $stripeToken;

        parent::__construct($attributes);

    }

    public function riot(){

        echo"We are rioting in stripe!";
    }



}