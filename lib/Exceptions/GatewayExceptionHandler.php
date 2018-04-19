<?php

namespace Stackout\Exceptions;

class GatewayExceptionHandler extends Throwable{


    protected $message = 'Unknown exception';

    // Redefine the Exception so Message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
       
    
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    final public function getMessage(){

        return $this->message();
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

?>