<?php

use TechnonextPlugin\TechnonextpayEnvReader;
use TechnonextPlugin\TechnonextpayConfig;

class TechnonextpayEnvReaderTest
{
    private $tempEnvFile;

    public function __construct()
    {
        // Create a temporary .env file for testing
        $this->tempEnvFile = tempnam(sys_get_temp_dir(), 'technonextpay_test_env');
        $envContent = "USERNAME=testuser\nPASSWORD=testpass\nMERCHANT_CODE=M12345\nTechnonextPay_API_ENDPOINT=https://api.test.com\nSIGNATURE=testsignature\nSUCCESS_URL=https://example.com/success\nFAILURE_URL=https://example.com/failure\nCANCEL_URL=https://example.com/cancel\nIPN_URL=https://example.com/ipn\nLOG_LOCATION=/tmp/logs\nCURLOPT_SSL_VERIFYPEER=1\n";
        file_put_contents($this->tempEnvFile, $envContent);
    }

    public function __destruct()
    {
        // Clean up temporary file
        if (file_exists($this->tempEnvFile)) {
            unlink($this->tempEnvFile);
        }
    }

    public function registerTests($runner)
    {
        $runner->addTest('TechnonextpayEnvReader - Object Creation', [$this, 'testObjectCreation']);
        $runner->addTest('TechnonextpayEnvReader - Load Environment', [$this, 'testLoadEnvironment']);
        $runner->addTest('TechnonextpayEnvReader - Get Config', [$this, 'testGetConfig']);
        $runner->addTest('TechnonextpayEnvReader - Invalid Path', [$this, 'testInvalidPath']);
    }

    public function testObjectCreation()
    {
        $reader = new TechnonextpayEnvReader($this->tempEnvFile);
        assert($reader instanceof TechnonextpayEnvReader, 'TechnonextpayEnvReader should be instantiable');
    }

    public function testLoadEnvironment()
    {
        $reader = new TechnonextpayEnvReader($this->tempEnvFile);
        $reader->load();

        assert(getenv('USERNAME') === 'testuser', 'USERNAME should be loaded from env file');
        assert(getenv('PASSWORD') === 'testpass', 'PASSWORD should be loaded from env file');
        assert(getenv('TechnonextPay_API_ENDPOINT') === 'https://api.test.com', 'API endpoint should be loaded');
    }

    public function testGetConfig()
    {
        $reader = new TechnonextpayEnvReader($this->tempEnvFile);
        $config = $reader->getConfig();

        assert($config instanceof TechnonextpayConfig, 'getConfig should return TechnonextpayConfig instance');
        assert($config->username === 'testuser', 'Username should be set from env');
        assert($config->password === 'testpass', 'Password should be set from env');
        assert($config->merchant_code === 'M12345', 'Merchant code should be set from env');
        assert($config->api_endpoint === 'https://api.test.com', 'API endpoint should be set from env');
        assert($config->success_url === 'https://example.com/success', 'Success URL should be set from env');
        assert($config->log_path === '/tmp/logs', 'Log path should be set from env');
        assert($config->ssl_verifypeer === '1', 'SSL verify peer should be set from env');
    }

    public function testInvalidPath()
    {
        try {
            new TechnonextpayEnvReader('/nonexistent/path/.env');
            assert(false, 'Should throw exception for invalid path');
        } catch (InvalidArgumentException $e) {
            assert(true, 'Should throw InvalidArgumentException for invalid path');
        }
    }
}