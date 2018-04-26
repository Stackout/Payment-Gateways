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
        
        // Find the User to Charge
        $user = User::find(1);

        // Charge the User
        $response = $user->charge($request, 5000);       

        // Check weather or not the card was declined or not
        if(!$response->valid())
            return redirect()->back()->withErrors($charge->errors);
        
        // Redirect back with a success message. (Or Continue forward )
        return redirect()->back()->with('success', $response->success);

    }


}



?>