<?php

/**
 * Test Runner for Technonextpay Plugin
 * Run all tests: php tests/run_tests.php
 */

require_once __DIR__ . '/../src/TechnonextpayException.php';
require_once __DIR__ . '/../src/TechnonextpayConfig.php';
require_once __DIR__ . '/../src/PaymentRequest.php';
require_once __DIR__ . '/../src/TechnonextpayValidation.php';
require_once __DIR__ . '/../src/Technonextpay.php';
require_once __DIR__ . '/../src/TechnonextpayEnvReader.php';

class TestRunner
{
    private $tests = [];
    private $passed = 0;
    private $failed = 0;

    public function addTest($testName, callable $testFunction)
    {
        $this->tests[$testName] = $testFunction;
    }

    public function run()
    {
        echo "🧪 Running Technonextpay Plugin Tests\n";
        echo "=====================================\n\n";

        foreach ($this->tests as $testName => $testFunction) {
            echo "Running: $testName... ";
            try {
                $testFunction();
                echo "✅ PASSED\n";
                $this->passed++;
            } catch (Exception $e) {
                echo "❌ FAILED: " . $e->getMessage() . "\n";
                $this->failed++;
            }
        }

        echo "\n=====================================\n";
        echo "Test Results: {$this->passed} passed, {$this->failed} failed\n";

        if ($this->failed > 0) {
            exit(1);
        }
    }
}

// Include all test files
$testFiles = [
    __DIR__ . '/PaymentRequestTest.php',
    __DIR__ . '/TechnonextpayConfigTest.php',
    __DIR__ . '/TechnonextpayValidationTest.php',
    __DIR__ . '/TechnonextpayEnvReaderTest.php',
    __DIR__ . '/TechnonextpayIntegrationTest.php'
];

$runner = new TestRunner();

foreach ($testFiles as $testFile) {
    if (file_exists($testFile)) {
        require_once $testFile;
        $className = basename($testFile, '.php');
        if (class_exists($className)) {
            $testInstance = new $className();
            if (method_exists($testInstance, 'registerTests')) {
                $testInstance->registerTests($runner);
            }
        }
    }
}

$runner->run();