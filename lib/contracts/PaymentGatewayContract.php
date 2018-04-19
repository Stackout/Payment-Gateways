<?php


namespace Stackout\PaymentGateways\Contracts;


interface PaymentGatewayContract{

    public function processPayment();
    public function authorize();


}