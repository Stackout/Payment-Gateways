<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Gateway.php extends the contract 
 *
 * Long description for file (if any)...
 *
 * PHP version 7.0+
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   PaymentProcessing
 * @package    PaymentGateway
 * @author     Original Author <ryan@heimech.com>
 * @copyright  2018 Heimech Studios
 * @link       http://github.com/stackout/payment-gateways
 * @since      File available since Release 1.0.0
 */


namespace Stackout\PaymentGateways;

use \Config;
use \Cache;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * This is a "Docblock Comment," also known as a "docblock."  The class'
 * docblock, below, contains a complete description of how to write these.
 */
class Gateway{

    /**
     * Stripe Constants
     *
     * @var constant[int] STRIPE = 0
     * @var constant[string] STRIPE_NAME = stripe_name
     */
    public const STRIPE = 0;
    public const STRIPE_NAME = 'stripe';
    
    /**
     * AUTHORIZE.NET Constants
     * @var constant[int] AUTHORIZENET = 1 
     * @var constant[string] AUTHORIZENET_NAME = authorize.net
     */
    public const AUTHORIZENET = 1;
    public const AUTHORIZENET_NAME = 'authorize.net';

    /**
     * PAYPAL PRO Constants
     * @var constant[int] PAYPAL = 2 
     * @var constant[string] PAYPAL_NAME = paypal
     */
    public const PAYPAL = 2;
    public const PAYPAL_NAME = 'paypal';

    /**
     * Default gateway constant
     * @var constant[int] __default = 2 
     * @var constant[int] __default_name = 2 
     */
    public const __default = self::STRIPE;
    public const __default_name = self::STRIPE_NAME;

    /**
     * Every gateway can potentially contain public and private keys. These are not stored here.
     *
     * @var string
     */
    protected $publicKey;

    /**
     * Every gateway can potentiall contain a private key to talk to the API. These are not stored here.
     *
     * @var string
     */
    private $privateKey;

    /**
     * Every gateway can potentiall contain a private key to talk to the API. These are not stored here.
     *
     * @var string
     */
    protected $isDevelopment;
    
    /**
     * This service tells us if we are in production or develpoment
     *
     * @var string
     */
    protected $service;
     
    /**
     * This service tells us waht cache driver we're using
     *
     * @var string
     */
    protected $cache;
    
    /**
     * This service tells us waht cache driver we're using
     *
     * @var string
     */
    protected $gatewayName;
     
     /**
     * This service tells us if we are in production or develpoment
     *
     * @var array
     */
    protected $attributes = array();

     /**
     * 
     * Laravel's request paramter
     * 
     * @var array
     */
    public $request;
     
     /**
     * This is a 'model' or 'object' we can access the attributes of our defined payment gateway.
     *
     * @var Model
     */
    public $customer;
     
     /**
     * This is a 'model' or 'object' we can access the attributes of our defined payment gateway.
     *
     * @var Model
     */
    public $amount;
     
     /**
     * This is a 'model' or 'object' we can access the attributes of our defined payment gateway.
     *
     * @var ChargeObject
     */
    public $charge;

     /**
     * Default representation of the credit card
     *
     * @var string Default Credit Card
     */
    public $default_card;

     /**
     * Default representation of the credit card
     *
     * @var string Default Credit Card
     */
    public $interruptible;

     /**
     * Declare error
     *
     * @var string Default Credit Card
     */
    public $errors;

     /**
     * Declare success message
     *
     * @var string Default Credit Card
     */
    public $success;

    /**
    * Declare error
    *
    * @var string Default Credit Card
    */
    public $errorResponseType;

    /**
    * Declare error
    *
    * @var string Default Credit Card
    */
    public $errorHttpStatus;

    /**
    * Creditcard Object
    *
    * @var string Default Credit Card Object
    */
    public $creditcard;

    /**
    * Bankaccount Object
    *
    * @var string
    */
    public $bankaccount;

    /**
    * Source Object
    *
    * @var string Source Object
    */
    public $source;

    /**
    * Obtains the last response from the gateway
    *
    * @var string Source Object
    */
    public $response;



    /**
     *  Allow overloading of constructor if we want to set our own private and public keys
     */
    public function __construct(Request $request = null, int $gateway = null, array $attributes = [], $privateKey = null, $publicKey = null, $currency = Currency::__default){

        // Check if we are in develpoment or production
        $this->isDevelopment = \Config::get('payment_gateways.development');

        // Load cache setting from config
        $this->cache = \Config::get('payment_gateways.cache');

        // Set which service to provide
        $this->service = ($this->isDevelopment) ? 'development' : 'production';

        // Set weather or not this is interruptable
        $this->interruptible = \Config::get('payment_gateways.interruptible');

        // Set the config path attribute
        $attributes['config'] = 'payment_gateways.' . $this->gatewayName . '.' . $this->service;

        // Set the config path attribute
        $attributes['currency'] = $currency;

        // Define Attributes
        $this->attributes = $attributes;

        // Set the default gateway by name if it is not already set
        if($this->gatewayName == null)
            $this->gatewayName = Config::get('payment_gateways.default');


    }

    /**
     * Magic Getter Method
     * @return attribute
     */
    public function __get($key){
        if (!$this->$key) 
            throw new \Exception ("Property {$key} is not defined.");
 
        return $this->$key;
    }

    /**
     * Magic Setter Method
     * @return void
     */
    public function __set($key, $value){$this->attributes[$key] = $value;}


    /**
     * Set all Attributes
     * @var array attributes 
     * 
     * @return void
     */
    public function setAttributes(array $attributes = []){
        foreach($attributes as $key => $value)
            $this->attributes[$key] = $value;     
    }

    /**
     * Set a single attribute
     * 
     * @var String key
     * @var String value
     * 
     * @return void
     */
    public function setAttribute($key, $value){
        $this->attributes[$key] = $value;
    }

    /**
     * @var String
     * 
     * @return Array
     */
    public function getAttribute($key){
        return $this->attributes[$key];
    }

    public function getPublicKey(){
        return $this->publicKey;
    }

    public function setPrivateKey($privateKey){
        $this->privateKey = $privateKey;
    }

    public function getPrivateKey(){
        return $this->privateKey;
    }

    public function setCurrency(string $currency){$this->currency = $currency;}

    public final static function default(){

        if(!Cache::has('payment_gateways:default'))
        {
            Cache::set('payment_gateways:default', Config::get('payment_gateways.default'));
            define(self::__default, Config::get('payment_gateways.default'));
        }

        return self::__default;

    }

    public function setCustomer($customer){
        $this->customer = $customer;
    }

    

    public function checkRequest(){        
        if($this->request == null)
            throw new \Exception('Invalid request.');
    }

    /**
     * Validation and errors
     * 
     * @return array
     */
    public function errors(){
        return $this->errors;
    }

    /**
     * Validator
     * 
     * @return bool
     */
    public function valid(){
        if(empty($this->errors))
            return true;        
        return false;
    }

    /**
     * Fails Method (opposite of valid)
     * 
     * @return bool
     */
    public function fails(){
        if(!empty($this->errors))
            return false;        
        return true;
    }

    /**
     * Set response error
     * @return void
     */
    public function setError(\Exception $e){
        $this->errors[] = $e->getMessage();
        $this->errorHttpStatus = $e->getHttpStatus();
        $this->errorResponseType = $e->getType();
    }

    /**
     *  Default Method for Getting Charge Object
     */
    public function getCharge(){
        return $this->charge;
    }

    public function getCurrency(){

        return $this->attributes['currency'];
    }

    /**
    * Pagiante an Array of Collectionable Objects
    *
    * @param array|Collection      $items
    * @param int   $perPage
    * @param int  $page
    * @param array $options
    *
    * @return LengthAwarePaginator
    */
    public function paginate($perPage = 15, $page = null, $options = [], $items = null)
    {
        if($items == null)
            throw new \Exception('Please define an array or a collection to paginate. Items cannot be null.');
        
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


}