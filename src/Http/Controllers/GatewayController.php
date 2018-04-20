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
    public function index(){

       


        // Get our default gateway processor
        return view('sgateway::stripe.checkout');

    }

    public function postCheckout(Request $request){        
        
        $user = User::find(1);
        $user->charge($request, 5000);

        

    }


}



?>