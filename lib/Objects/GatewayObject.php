<?php

namespace Stackout\Objects;

class GatewayObject{



    /**
     * 
     * @var String
     */
    protected $metadata;

    /**
     * 
     * @var Array
     */
    protected $attributes = [];


    /**
     * Set an attribute for object
     */
    public function setAttribute($key, $value){

        $this->attributes[$key] = $value;

    }
    
    /**
     * Set an attribute for object
     */
    public function getAttribute($key){

        return $this->attributes[$value];

    }


    /**
     * Set Cosntructor
     */
    public function __construct(){

        // nothing to see here yet

    }

}