<?php

use TechnonextPlugin\PaymentRequest;

class PaymentRequestTest
{
    public function registerTests($runner)
    {
        $runner->addTest('PaymentRequest - Object Creation', [$this, 'testObjectCreation']);
        $runner->addTest('PaymentRequest - Property Assignment', [$this, 'testPropertyAssignment']);
        $runner->addTest('PaymentRequest - Required Fields', [$this, 'testRequiredFields']);
    }

    public function testObjectCreation()
    {
        $request = new PaymentRequest();
        assert($request instanceof PaymentRequest, 'PaymentRequest should be instantiable');
    }

    public function testPropertyAssignment()
    {
        $request = new PaymentRequest();

        // Test basic property assignment
        $request->currency_code = 'BDT';
        $request->payable_amount = 100.50;
        $request->customer_name = 'John Doe';
        $request->customer_email = 'john@example.com';
        $request->contact_number = '12345678901';

        assert($request->currency_code === 'BDT', 'Currency code should be set');
        assert($request->payable_amount === 100.50, 'Payable amount should be set');
        assert($request->customer_name === 'John Doe', 'Customer name should be set');
        assert($request->customer_email === 'john@example.com', 'Customer email should be set');
        assert($request->contact_number === '12345678901', 'Contact number should be set');
    }

    public function testRequiredFields()
    {
        $request = new PaymentRequest();

        // Check that all expected properties exist
        $expectedProperties = [
            'currency_code',
            'payable_amount',
            'product_amount',
            'preferred_channel',
            'allowed_bin',
            'discount_amount',
            'disc_percent',
            'customer_name',
            'customer_phone',
            'customer_email',
            'contact_number',
            'customer_city',
            'customer_state',
            'customer_postcode',
            'customer_country',
            'customer_primaryAddress',
            'customer_secondaryAddress',
            'shipping_address',
            'shipping_city',
            'shipping_country',
            'received_person_name',
            'shipping_phone_number',
            'mdf1',
            'mdf2',
            'mdf3',
            'mdf4'
        ];

        foreach ($expectedProperties as $property) {
            assert(property_exists($request, $property), "Property $property should exist");
        }
    }
}