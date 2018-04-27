<?php

namespace Stackout\PaymentGateways;


use Stackout\PaymentGateways\Traits\HasDefusableKeys;

use Illuminate\Database\Eloquent\Model;

class GatewaySetting extends Model
{

    use HasDefusableKeys;

    /**
     *  Set Table name for Gateway Settings
     */
    protected $table = 'gateway_settings';

    /**
     *  Set Table name for Gateway Settings
     */
    protected $connection = 'mysql';

    /**
     * Set fillable proeprty
     */
    protected $fillable = ['value'];

    /**
     * Set dates proeprty
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Set defuseable key identifier
     */
    protected $defuseableKeyIdentifier = '_secret';


}
