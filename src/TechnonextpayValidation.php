<?php

namespace TechnonextPlugin;

/**
 *
 * PHP Plug-in service to provide Technonextpay get way services.
 *
 * @author 
 * @since 
 */
class TechnonextpayValidation
{

    /**
     * Prepare a method for checking internet connection from client-side
     *
     * @return bool $is_conn
     */
    function checkInternetConnection()
    {
        $connected = @fsockopen('www.google.com', 80);

        if ($connected) {
            $is_conn = true; //action when connected
            fclose($connected);
        } else {
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    /**
     * Validate  payload required data
     *
     * @param mixed $payload_data
     * This is a validation method whitch has all of payload data and it sends data for null & formate validation.
     */
    function Validation($payload_data)
    {
        $payload_data = (json_decode($payload_data));
  
        return ($this->emptyCheck(
            'Order ID',
            $payload_data->order_id
        ) && $this->emptyCheck(
            'Amount',
            $payload_data->order_information->payable_amount
        ) && $this->emptyCheck(
            'Currency',
            $payload_data->order_information->currency_code
        ) && $this->emptyCheck(
            'IPN Url',
            $payload_data->ipn_url
        ) && $this->emptyCheck(
            'Success Url',
            $payload_data->success_url
        ) && $this->emptyCheck(
            'Cancel Url',
            $payload_data->cancel_url
        ) && $this->emptyCheck(
            'Failure Url',
            $payload_data->failure_url
        ) && $this->emptyCheck(
            'Customer Name',
            $payload_data->customer_information->name
        ) && $this->phoneCheck(
            $payload_data->customer_information->contact_number
        ) && $this->emailCheck(         
            $payload_data->customer_information->email
        ) && $this->emptyCheck(
            'Customer Primary Address',
            $payload_data->customer_information->primaryAddress
        ) && $this->emptyCheck(
            'Customer City',
            $payload_data->customer_information->city
        ) && $this->emptyCheck(
            'Customer State',
            $payload_data->customer_information->state
        ) && $this->emptyCheck(
            'Customer Postcode',
            $payload_data->customer_information->postcode
        ) && $this->emptyCheck(
            'Customer Country',
            $payload_data->customer_information->country
        ));
    }


    /**
     * Checks whether a data item is null or empty.
     *
     * @param mixed $attr
     * @param mixed $data
     * @return bool
     */
    function emptyCheck($attr, $data)
    {
        if (!isset($data) || empty($data)) {
            if ($data == 0) return true;
            error_log("$attr is null or empty");
            return false;
        }
        return true;
    }

    /**
     * Checks for valid email format.
     * @param mixed $email
     * @return bool
     */
    function emailCheck($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log("Email format is invalid");
            return false;
        } else {
            return true;
        }
    }

    /**
     * This method is for checking phone number format.
     * @param mixed $phone
     * @return bool
     */
    function phoneCheck($phone)
    {
        if (preg_match("/^([0-9]{11})$/", $phone)) {
            return true;
        } else {
            error_log("Phone number is not valid");
            return false;
        }
    }
}
