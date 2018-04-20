<?php


namespace Stackout\PaymentGateways;


interface SubscriptionContract{


    /**
     * All methods pertaining to recurring billing or subscriptions
     */
    public function createSubscription();

    public function retrieveSubscription();

    public function updateSubscription();

    public function deleteSubscription();


}