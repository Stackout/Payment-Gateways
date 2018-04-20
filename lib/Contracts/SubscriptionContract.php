<?php


namespace Stackout\PaymentGateways\Contracts;


interface SubscriptionContract{


    /**
     * All methods pertaining to recurring billing or subscriptions
     */
    public function createSubscription($data);

    public function retrieveSubscription($id);

    public function updateSubscription($id, $data);

    public function deleteSubscription($id);


}