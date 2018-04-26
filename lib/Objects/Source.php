<?php

namespace Stackout\Objects;

// Create a source object, all sources have addresses, objects, and ids.
class Source{

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
     * 
     * @var String
     */
    protected $metadata;

    /**
     * Construtor
     */
    public function __construct(){

        // Nothing to do here yet

    }

}


?>