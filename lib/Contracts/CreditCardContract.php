<?php


namespace Stackout\PaymentGateways;


interface CreditCardContract{

    /**
     * All methods pertaining to customer profiles
     */
    public function createCard();

    public function retrieveCard();

    public function saveCard();

    public function deleteCard();

    public function getAllCards();

}