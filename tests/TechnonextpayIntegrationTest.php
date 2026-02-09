<?php

use TechnonextPlugin\Technonextpay;
use TechnonextPlugin\TechnonextpayConfig;
use TechnonextPlugin\PaymentRequest;
use TechnonextPlugin\TechnonextpayException;

class TechnonextpayIntegrationTest
{
    private $config;
    private $technonextpay;

    public function __construct()
    {
        $this->config = new TechnonextpayConfig();
        $this->config->username = 'testuser';
        $this->config->password = 'testpass';
        $this->config->merchant_code = 'M12345';
        $this->config->api_endpoint = 'https://httpbin.org'; // Using httpbin for testing
        $this->config->success_url = 'https://example.com/success';
        $this->config->failure_url = 'https://example.com/failure';
        $this->config->cancel_url = 'https://example.com/cancel';
        $this->config->ipn_url = 'https://example.com/ipn';
        $this->config->log_path = '/tmp';
        $this->config->ssl_verifypeer = false; // For testing
        $this->config->order_prefix = 'TEST_';

        $this->technonextpay = new Technonextpay($this->config);
    }

    public function registerTests($runner)
    {
        $runner->addTest('Technonextpay - Object Creation', [$this, 'testObjectCreation']);
        $runner->addTest('Technonextpay - Prepare Transaction Payload', [$this, 'testPrepareTransactionPayload']);
        $runner->addTest('Technonextpay - Get Client IP', [$this, 'testGetClientIpOrHost']);
        $runner->addTest('Technonextpay - Validation Failure', [$this, 'testValidationFailure']);
        $runner->addTest('Technonextpay - Internet Connection Check', [$this, 'testInternetConnection']);
    }

    public function testObjectCreation()
    {
        assert($this->technonextpay instanceof Technonextpay, 'Technonextpay should be instantiable');
    }

    public function testPrepareTransactionPayload()
    {
        $request = new PaymentRequest();
        $request->currency_code = 'BDT';
        $request->payable_amount = 100.50;
        $request->customer_name = 'John Doe';
        $request->customer_email = 'john@example.com';
        $request->contact_number = '12345678901';
        $request->customer_primaryAddress = '123 Main St';
        $request->customer_city = 'Dhaka';
        $request->customer_state = 'Dhaka';
        $request->customer_postcode = '1200';
        $request->customer_country = 'Bangladesh';
        $request->preferred_channel = 'VISA';
        $request->mdf1 = 'test1';
        $request->mdf2 = 'test2';

        $payload = $this->technonextpay->prepareTransactionPayload($request);
        $decoded = json_decode($payload, true);

        assert(is_string($payload), 'Payload should be a string');
        assert(json_last_error() === JSON_ERROR_NONE, 'Payload should be valid JSON');

        // Check structure
        assert(isset($decoded['order_id']), 'Order ID should be set');
        assert(isset($decoded['security']), 'Security section should exist');
        assert(isset($decoded['customer_information']), 'Customer information should exist');
        assert(isset($decoded['order_information']), 'Order information should exist');

        // Check values
        assert($decoded['order_information']['payable_amount'] === 100.50, 'Payable amount should be set');
        assert($decoded['order_information']['currency_code'] === 'BDT', 'Currency code should be set');
        assert($decoded['customer_information']['name'] === 'John Doe', 'Customer name should be set');
        assert($decoded['customer_information']['email'] === 'john@example.com', 'Customer email should be set');
        assert($decoded['customer_information']['contact_number'] === '12345678901', 'Contact number should be set');
        assert($decoded['mdf1'] === 'test1', 'MDF1 should be set');
        assert($decoded['mdf2'] === 'test2', 'MDF2 should be set');
    }

    public function testGetClientIpOrHost()
    {
        $ip = $this->technonextpay->getClientIpOrHost();

        // Should return either an IP or hostname
        assert(is_string($ip), 'Should return a string');
        assert(!empty($ip), 'Should not be empty');
    }

    public function testValidationFailure()
    {
        $request = new PaymentRequest();
        // Leave required fields empty to trigger validation failure
        // Note: This test expects the validation to fail, but the exception handling
        // in TechnonextpayException exits the script, so we test the validation directly

        $validator = new \TechnonextPlugin\TechnonextpayValidation();

        // Test with invalid payload
        $invalidPayload = json_encode([
            'security' => ['username' => '', 'password' => 'pass'], // empty username
        ]);

        ob_start();
        $result = $validator->Validation($invalidPayload);
        ob_end_clean();

        assert($result === false, 'Invalid payload should return false');
    }

    public function testInternetConnection()
    {
        // Test the checkInternetConnection method from TechnonextpayValidation
        $validator = new \TechnonextPlugin\TechnonextpayValidation();
        $hasConnection = $validator->checkInternetConnection();

        assert(is_bool($hasConnection), 'Should return boolean');
    }
}