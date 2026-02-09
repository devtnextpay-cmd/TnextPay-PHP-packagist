<?php

use TechnonextPlugin\TechnonextpayConfig;

class TechnonextpayConfigTest
{
    public function registerTests($runner)
    {
        $runner->addTest('TechnonextpayConfig - Object Creation', [$this, 'testObjectCreation']);
        $runner->addTest('TechnonextpayConfig - Property Assignment', [$this, 'testPropertyAssignment']);
        $runner->addTest('TechnonextpayConfig - Required Properties', [$this, 'testRequiredProperties']);
        $runner->addTest('TechnonextpayConfig - Default Values', [$this, 'testDefaultValues']);
    }

    public function testObjectCreation()
    {
        $config = new TechnonextpayConfig();
        assert($config instanceof TechnonextpayConfig, 'TechnonextpayConfig should be instantiable');
    }

    public function testPropertyAssignment()
    {
        $config = new TechnonextpayConfig();

        $config->username = 'testuser';
        $config->password = 'testpass';
        $config->merchant_code = 'M12345';
        $config->api_endpoint = 'https://api.example.com';
        $config->success_url = 'https://example.com/success';
        $config->failure_url = 'https://example.com/failure';
        $config->cancel_url = 'https://example.com/cancel';
        $config->ipn_url = 'https://example.com/ipn';
        $config->log_path = '/var/log/technonextpay';
        $config->ssl_verifypeer = true;
        $config->order_prefix = 'ORDER_';

        assert($config->username === 'testuser', 'Username should be set');
        assert($config->password === 'testpass', 'Password should be set');
        assert($config->merchant_code === 'M12345', 'Merchant code should be set');
        assert($config->api_endpoint === 'https://api.example.com', 'API endpoint should be set');
        assert($config->success_url === 'https://example.com/success', 'Success URL should be set');
        assert($config->failure_url === 'https://example.com/failure', 'Failure URL should be set');
        assert($config->cancel_url === 'https://example.com/cancel', 'Cancel URL should be set');
        assert($config->ipn_url === 'https://example.com/ipn', 'IPN URL should be set');
        assert($config->log_path === '/var/log/technonextpay', 'Log path should be set');
        assert($config->ssl_verifypeer === true, 'SSL verify peer should be set');
        assert($config->order_prefix === 'ORDER_', 'Order prefix should be set');
    }

    public function testRequiredProperties()
    {
        $config = new TechnonextpayConfig();

        $expectedProperties = [
            'username',
            'password',
            'signature',
            'merchant_code',
            'api_endpoint',
            'success_url',
            'failure_url',
            'cancel_url',
            'ipn_url',
            'log_path',
            'ssl_verifypeer',
            'order_prefix'
        ];

        foreach ($expectedProperties as $property) {
            assert(property_exists($config, $property), "Property $property should exist");
        }
    }

    public function testDefaultValues()
    {
        $config = new TechnonextpayConfig();

        // Properties should be null by default (public properties in PHP)
        assert($config->username === null, 'Username should be null by default');
        assert($config->password === null, 'Password should be null by default');
        assert($config->api_endpoint === null, 'API endpoint should be null by default');
    }
}