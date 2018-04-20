<?php

namespace Stackout\Http\Controllers;

use App\Http\Controllers\Controller;

use Stackout\PaymentGateways\Gateway;
use Stackout\PaymentGateways\GatewayProcessor;
use Illuminate\Http\Request;


use App\User;
class GatewayController extends Controller
{

    protected $gatewayProcessor;

    public function __construct(){


    }


    /**
     * Dump and Test our Gateways to our Payment Processing Platforms
     */
    public function index(Request $request){


        $gateway = GatewayProcessor::get($request);
        
        $user = User::find(1);
        $gateway->customer = $user;
        $gateway->amount = 50.32;
        
        // Get our default gateway processor
        return view('sgateway::stripe.checkout');

    }

    public function postCheckout(Request $request){
        
        
        $gateway = GatewayProcessor::get($request);
        
        $user = User::find(1);
        $gateway->customer = $user;
        $gateway->amount = 50.32;

        // Created a customer if it doesn't already exist.
        // If customer does exist, retrieve it
        $user->charge();


    }


}



?>