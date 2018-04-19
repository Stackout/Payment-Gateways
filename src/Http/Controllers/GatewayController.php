<?php

namespace Stackout\Http\Controllers;

use App\Http\Controllers\Controller;

use Stackout\PaymentGateways;

class GatewayController extends Controller
{

    /**
     * Dump and Test our Gateways to our Payment Processing Platforms
     */
    public function index(){

        return view('sgateway::layouts.main');

    }


}



?>