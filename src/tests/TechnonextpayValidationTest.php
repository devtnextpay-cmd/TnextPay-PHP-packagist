<?php

namespace TechnonextPlugin;

/**
 * Basic unit tests for TechnonextpayValidation class
 * Run with: php tests/TechnonextpayValidationTest.php
 */

require_once __DIR__ . '/../TechnonextpayValidation.php';

class TechnonextpayValidationTest
{
    private $validator;

    public function __construct()
    {
        $this->validator = new TechnonextpayValidation();
    }

    public function testEmptyCheck()
    {
        // Test valid data
        assert($this->validator->emptyCheck('Test', 'valid') === true, 'Valid data should return true');

        // Test empty string
        ob_start();
        $result = $this->validator->emptyCheck('Test', '');
        ob_end_clean();
        assert($result === false, 'Empty string should return false');

        // Test null
        ob_start();
        $result = $this->validator->emptyCheck('Test', null);
        ob_end_clean();
        assert($result === false, 'Null should return false');

        // Test zero (should be allowed)
        assert($this->validator->emptyCheck('Test', 0) === true, 'Zero should return true');

        echo "✓ emptyCheck tests passed\n";
    }

    public function testEmailCheck()
    {
        // Test valid email
        assert($this->validator->emailCheck('test@example.com') === true, 'Valid email should return true');

        // Test invalid email
        ob_start();
        $result = $this->validator->emailCheck('invalid-email');
        ob_end_clean();
        assert($result === false, 'Invalid email should return false');

        echo "✓ emailCheck tests passed\n";
    }

    public function testPhoneCheck()
    {
        // Test valid phone (11 digits)
        assert($this->validator->phoneCheck('12345678901') === true, 'Valid 11-digit phone should return true');

        // Test invalid phone (too short)
        ob_start();
        $result = $this->validator->phoneCheck('1234567890');
        ob_end_clean();
        assert($result === false, '10-digit phone should return false');

        // Test invalid phone (too long)
        ob_start();
        $result = $this->validator->phoneCheck('123456789012');
        ob_end_clean();
        assert($result === false, '12-digit phone should return false');

        // Test invalid phone (contains letters)
        ob_start();
        $result = $this->validator->phoneCheck('1234567890a');
        ob_end_clean();
        assert($result === false, 'Phone with letters should return false');

        echo "✓ phoneCheck tests passed\n";
    }

    public function testValidation()
    {
        // Test valid payload
        $validPayload = json_encode([
            'order_id' => 'test123',
            'security' => ['username' => 'user', 'password' => 'pass'],
            'order_information' => ['payable_amount' => 100, 'currency_code' => 'BDT'],
            'ipn_url' => 'http://example.com/ipn',
            'success_url' => 'http://example.com/success',
            'cancel_url' => 'http://example.com/cancel',
            'failure_url' => 'http://example.com/failure',
            'customer_information' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'contact_number' => '12345678901',
                'primaryAddress' => '123 Main St',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'postcode' => '1200',
                'country' => 'Bangladesh'
            ]
        ]);

        assert($this->validator->Validation($validPayload) === true, 'Valid payload should return true');

        // Test invalid payload (missing required field)
        $invalidPayload = json_encode([
            'order_id' => 'test123',
            'security' => ['username' => '', 'password' => 'pass'], // empty username
            'order_information' => ['payable_amount' => 100, 'currency_code' => 'BDT'],
            'ipn_url' => 'http://example.com/ipn',
            'success_url' => 'http://example.com/success',
            'cancel_url' => 'http://example.com/cancel',
            'failure_url' => 'http://example.com/failure',
            'customer_information' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'contact_number' => '12345678901',
                'primaryAddress' => '123 Main St',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'postcode' => '1200',
                'country' => 'Bangladesh'
            ]
        ]);

        ob_start();
        $result = $this->validator->Validation($invalidPayload);
        ob_end_clean();
        assert($result === false, 'Invalid payload should return false');

        echo "✓ Validation tests passed\n";
    }

    public function runAllTests()
    {
        echo "Running TechnonextpayValidation tests...\n\n";

        $this->testEmptyCheck();
        $this->testEmailCheck();
        $this->testPhoneCheck();
        $this->testValidation();

        echo "\n✅ All tests passed!\n";
    }
}

// Run tests if this file is executed directly
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    $test = new TechnonextpayValidationTest();
    $test->runAllTests();
}