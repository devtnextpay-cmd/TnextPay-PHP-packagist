<?php

namespace TechnonextPlugin;

/**
 * PHP plugin class PaymentRequest to store payload values.
 *
 * @author 
 * @since 
 */
class PaymentRequest
{
    /** Payment currency; e.g. BDT, USD etc */
    public $currency_code;
    /** Payment amount to be debited from consumer */
    public $payable_amount;
    /** Product amount */
    public $product_amount;
    /** Preferred channel */
    public $preferred_channel;
    /** Allowed BIN numbers    */
    public $allowed_bin;
    /** technonextpay discountAmount */
    public $discount_amount;
    /** technonextpay discPercent */
    public $disc_percent;
    /** technonextpay customerName */
    public $customer_name;
    /** technonextpay customerPhone */
    public $customer_phone;
    /** technonextpay customerEmail */
    public $customer_email;
    /** technonextpay contactNumber */
    public $contact_number;
    /** technonextpay customerCity */
    public $customer_city;
    /** technonextpay customerState */
    public $customer_state;
    /** technonextpay customerPostcode */
    public $customer_postcode;
    /** technonextpay customerCountry */
    public $customer_country;
    /* technonextpay customerPrimaryAddress */
    public $customer_primaryAddress;
    /* technonextpay customerSecondaryAddress */
    public $customer_secondaryAddress;
    /* Shipping information. optional */
    public $shipping_address;
    public $shipping_city;
    public $shipping_country;
    public $received_person_name;
    public $shipping_phone_number;
    /** Optional values if needed*/
    public $mdf1;
    public $mdf2;
    public $mdf3;
    public $mdf4;
}

