<?php

namespace Stackout\Http\Controllers;

use App\Http\Controllers\Controller;

use Stackout\PaymentGateways\Gateway;
use Stackout\PaymentGateways\GatewayProcessor;
use Illuminate\Http\Request;


use App\User;
class AdminGatewayController extends Controller
{

    protected $gatewayProcessor;

    protected $setting_model;

    protected $settings = [];

    public function __construct(){

        $this->middleware('auth');

        // Determine which model we're using to find the settings.
        $this->setting_model = \Config::get('payment_gateways.settings_model');

        // Get all settings pertaining to the model (or class)
        $settings = $this->setting_model::all();

        // Map Settings as Key => value
        foreach($settings as $setting)
            $this->settings[$setting->key] = $setting->value;

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