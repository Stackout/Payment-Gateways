<?php

namespace Stackout\Objects;


// Create a source object, all sources have addresses, objects, and ids.
class Source extends GatewayObject{

    /**
     * @var String
     */
    protected $id;

    /**
     * @var String
     */
    protected $object;
    
    /**
     * @var Array
     */
    protected $address = [];

    /**
     * @var String
     */
    protected $owner;

    /**
     * @var String
     */
    protected $funding;

    /**
     * 
     * @var String
     */
    protected $country;


    /**
     * Construtor
     */
    public function __construct(){

        // Nothing to do here yet

        parent::__construct();
    }

    /**
     * Magic getters to get the protected properties
     */
    public function __get($key){
        return $this->$key;
    }
    
    /**
     * Mafic Setter method to set the protected proeprties
     */
    public function __set($key, $value){
        $this->$key = $value;
    }

    public function setAddress($line1, $line2, $city, $state, $zipcode){

        $this->address['line1'] = $line1;
        $this->address['line2'] = $line2;
        $this->address['city'] = $city;
        $this->address['state'] = $state;
        $this->address['zipcode'] = $zipcode;

    }

    public function getAddress($value = null){

        if($value == null)
            return $this->address;

        return $this->address[$value];

    }



}


?>