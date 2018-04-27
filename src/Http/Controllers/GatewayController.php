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

    protected $setting_model;

    protected $settings;

    public function __construct(){

        // Determine which model we're using to find the settings.
        $this->setting_model = \Config::get('payment_gateways.settings_model');

        // Get all settings pertaining to the model (or class)
        $settings = $this->setting_model::all();

        // Map Settings as Key => value
        foreach($settings as $setting)
            $this->settings[$setting->key] = $setting->value;

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
        $response = $user->charge(5000);       

        // Check weather or not the card was declined or not
        if(!$response->valid())
            return redirect()->back()->withErrors($response->errors);
        
        
        $creditcard = $response->creditcard();

        dd($creditcard);

        // Redirect back with a success message. (Or Continue forward )
        //return redirect()->back()->with('success', $response->success);

    }

    public function settings(){

        return view('sgateway::pages.admin')->with('settings', $this->settings);

    }

    public function postSettings(Request $request){

        $fields = $request->except(['_token', 'save']);

        foreach($fields as $key => $value){

            $setting = ($this->setting_model::where('key', $key))->first();

            // If setting doesn't exist, create it
            if($setting === null){
                
                $setting = new $this->setting_model;
                $setting->key = $key;
                $setting->value = ($value ?: '');
                $setting->save();

            }
            else
            {
                $setting->value = $value;
                $setting->save();
            }

        }

        return redirect()->back()->with('success', 'Settings updated.');
        
    }

}



?>