<?php


namespace Stackout\PaymentGateways;

use Stackout\Objects\Creditcard;
use Stackout\Objects\Bankaccount;

use \Config;
use \Cache;
use Illuminate\Http\Request;


use PayPal\v1\Payments\PaymentCreateRequest;
use PayPal\Core\PayPalHttpClient;
use PayPal\Core\SandboxEnvironment;


class PaypalPaymentGateway extends Gateway{

    /**
     * Our Paypal Environment
     * 
     * @var String
     */
    protected $environment;

    /**
     * Version Variable
     * 
     * @var String
     */
    protected $version;

    /**
     * Endpoint Variable
     * 
     * @var String
     */
    protected $endpoint;

    /**
     * Username Variable
     * 
     * @var String
     */
    protected $username;

    /**
     * Password Variable
     * 
     * @var String
     */
    private $password;

    /**
     * Signature Variable
     * 
     * @var String
     */
    protected $signature;

    /**
     * Signature Variable
     * 
     * @var String
     */
    protected $paypalHttpRequest;

    /**
     * Signature Variable
     * 
     * @var String
     */
    protected $cancel_url;

    /**
     * Signature Variable
     * 
     * @var String
     */
    protected $return_url;

    /**
     * Signature Variable
     * 
     * @var String
     */
    protected $client;

    /**
     * Signature Variable
     * 
     * @var String
     */
    protected $paypalCustomer;

    /**
     * Signature Variable
     * 
     * @var String
     */
    protected $payment_method;

    /**
     * Transaction type variable
     * 
     * @var String
     */
    protected $transaction;


    /**
     * Constructor
     */
    public function __constructor(Request $request = null, array $attributes = [], $currency = Currency::__default){

        // Set the gateway name for this instance
        if($this->gatewayName != null)
            $this->gatewayName = Gateway::PAYPAL_NAME;

        parent::__construct($request, $attributes, Gateway::PAYPAL);
            
        $this->init();

    }



    /**
     * Initilize the connection to the paypal interface
     */
    public function init(){

        // Setup Application Environment
        if($this->environment != null && $this->client != null)
            $this->setupApplicationEnvironment();

    }


    /**
     * Setup enviornment (Required by Paypal PRO)
     */
    private function setupApplicationEnvironment(){

        // TODO: MAKE MORE EFFICENT WITH CACHING
        if($this->version == null)
            $this->version = env('PAYPAL_VERSION', 'dev-2.0-beta');

        if($this->endpoint == null)
            $this->endpoint = env('PAYPAL_ENDPOINT');
        
        if($this->username == null)
            $this->username = env('PAYPAL_USERNAME');

        if($this->password == null)
            $this->password = env('PAYPAL_PASSWORD');

        if($this->signature == null)
            $this->signature = env('PAYPAL_SIGNATURE');

        if($this->isDirect == null)
            $this->isDirect = env('PAYPAL_DIRECT', true);

        if($this->payment_method == null)        
            $this->payment_method = ($this->isDirect) ? Objects::CREDIT_CARD : env('PAYMENT_METHOD');

        // Set our enviornment based on production or development
        $this->environment = (!$this->isDevelopment) ? new ProductionEnvironment() : new SandboxEnvironment();

        $this->client = new PayPalHttpClient($this->environment);

    }


    public function charge(){

        try{

            $this->createCharge();



        }catch(\HttpException $e){
            
            $this->errors[] = $e->getMessage();
            
        }catch (\Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $this->errors[] = $e->getMessage();

        }
        
        return $this;
    }

    /**
     * Set Paypal's Intent
     */
    public function setIntent($intent){

        $this->intent = $intent;
        $this->paypalHttpRequest['intent'] = $intent;

        return $this;
    }

    /**
     * Create Charge Object
     */
    public function createCharge(){

        if($this->charge == null)
            $this->charge = new PaymentCreateRequest();

        // If we haven't set our customer object, throw an error.
        if($this->customer == null)
            throw new Exception('Customer model or object not defined.');

        // If paypals http request is empty, throw exception
        if($this->paypalHttpRequest == null)
            throw new \Exception('Paypal Http Request is Empty');

        if(!array_key_exists('intent', $this->paypalHttpRequest))
            throw new \Exception("Paypal request missing 'intent' paramter.");
        
        if(!array_key_exists('transactions', $this->paypalHttpRequest))
            throw new \Exception("Paypal request missing 'transaction' paramter.");

        if(!$this->isDirect && !array_key_exists('redirect_urls', $this->paypalHttpRequest))
            throw new \Exception("Paypal request missing 'redirect_urls' paramter.");

        if(!array_key_exists('payer', $this->paypalHttpRequest))
            throw new \Exception("Paypal request missing 'payer' paramter.");

        $this->charge->body = $this->paypalHttpRequest;

        return $this;
    }

    public function retrieveCharge(){

        return $this->charge;

    }

    public function setRedirects($cancel_url, $return_url){

        $this->cancel_url = $cancel_url;
        $this->return_url = $return_url;

        // Set the redirect urls
        $this->paypalHttpRequest['redirect_urls'] = [
            'cancel_url' => $this->cancel_url,
            'return_url' => $this->return_url
        ];

        return $this;

    }

    public function setTransaction($transaction){       
        $this->transaction = $transaction; 
        $this->paypalHttpRequest['transaction'] = $transaction;
        return $this;
    }

    /**
     * This sets up our application for a direct payment. 
     * This is the default way of making a payment through the 
     * gateway.
     * 
     * ---
     * 
     * If you want to change weather it is direct or through express paypal checkout
     * then change the .env file to reflect 
     * 
     * .env
     * PAYPAL_DIRECT=FALSE
     * 
     * @var String $payment_method
     */
    public function setPayer($payment_method){

        $this->paypalHttpRequest['payer']['payment_method'] = $payment_method;

        return $this;
    }


    public function createCustomer(){

        // If customer defined, then it is already created.
        if($this->paypalCustomer == null)
            $this->paypalCustomer = $this->retrieveCustomer();

        return $this;
    }

    public function retrieveCustomer(){

        if($this->paypalCustomer != null)
            return $this->paypalCustomer;
        
        if($this->customer == null)
            throw new \Expcetion('Customer model or object not defined.');

        // Set the customers
        $this->paypalCustomer['email'] = $this->customer->email;

    }

}