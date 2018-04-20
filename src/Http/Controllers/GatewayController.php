<?php

namespace Stackout\Http\Controllers;

use App\Http\Controllers\Controller;

use Stackout\PaymentGateways\Gateway;
use Stackout\PaymentGateways\GatewayProcessor;

class GatewayController extends Controller
{

    /**
     * Dump and Test our Gateways to our Payment Processing Platforms
     */
    public function index(){

        
        $gatewayProcessor = (new GatewayProcessor(Gateway::STRIPE))->getGateway();        
        return view('sgateway::stripe.checkout');

    }


}



?>