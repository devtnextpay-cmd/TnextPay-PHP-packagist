<?php

namespace TechnonextPlugin;

class TechnonextpayConfig
{
    /** Technonextpay merchant code; e.g. TNX12345 */
    public $merchant_code;
    
    /** Technonextpay merchant API key  */  
    public $api_key;

    /** Technonextpay merchant API secret  */
    public $api_secret;

    /** Technonextpay payment gateway API endpoint; e.g. https://sandbox.Technonextpayment.com */
    public $api_endpoint;

    /** URL to redirect after completion of a payment. e.g. https://sandbox.Technonextpayment.com/success */
    public $success_url;

    /** URL to redirect after completion of a payment. e.g. https://sandbox.Technonextpayment.com/failure */
    public $failure_url;

    /** URL to redirect after completion of a payment. e.g. https://sandbox.Technonextpayment.com/cancel */
    public $cancel_url;

    /** IPN URL */
    public $ipn_url;

    /** Log path or directory to store PHP plugin logs */
    public $log_path;


    /** Merchant prefix used to generate order id */
    public $ssl_verifypeer;

    public $order_prefix;
}