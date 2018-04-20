<?php


namespace Stackout\PaymentGateways;


interface ChargeContract{

    // Creates and captures the charge
    public function charge();

}

?>