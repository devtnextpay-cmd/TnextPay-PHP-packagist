# Technonextpay Plugin Test Suite

This directory contains comprehensive unit and integration tests for the Technonextpay PHP plugin.

## Test Structure

- `run_tests.php` - Main test runner that executes all test suites
- `PaymentRequestTest.php` - Tests for the PaymentRequest data class
- `TechnonextpayConfigTest.php` - Tests for configuration management
- `TechnonextpayEnvReaderTest.php` - Tests for environment variable loading
- `TechnonextpayIntegrationTest.php` - Integration tests for the main Technonextpay class
- `TechnonextpayValidationTest.php` - Tests for validation logic (also in src/tests/)

## Running Tests

### Run All Tests
```bash
php tests/run_tests.php
```

### Run Individual Test Files
```bash
php tests/PaymentRequestTest.php
php tests/TechnonextpayConfigTest.php
# etc.
```

## Test Coverage

The test suite covers:

### Unit Tests
- **PaymentRequest**: Object creation, property assignment, required fields validation
- **TechnonextpayConfig**: Configuration object creation and property management
- **TechnonextpayEnvReader**: Environment file loading and configuration parsing
- **TechnonextpayValidation**: Input validation, email/phone checking, payload validation

### Integration Tests
- **Technonextpay**: Transaction payload preparation, client IP detection, validation failure handling
- **HTTP Communication**: Error handling and response processing
- **Exception Handling**: Custom exception behavior

## Test Results

When tests pass, you'll see:
```
🧪 Running Technonextpay Plugin Tests
=====================================

Running: [Test Name]... ✅ PASSED
...

=====================================
Test Results: X passed, 0 failed
```

## Adding New Tests

To add new tests:

1. Create a new test class that implements a `registerTests($runner)` method
2. Add test methods that use `assert()` for validation
3. Register the test class in `run_tests.php`

Example:
```php
class MyNewTest {
    public function registerTests($runner) {
        $runner->addTest('My Test', [$this, 'myTestMethod']);
    }

    public function myTestMethod() {
        assert(true === true, 'This should always pass');
    }
}
```

## Test Dependencies

Tests require:
- PHP 7.4+
- Write access to `/tmp` for temporary test files
- Internet connection for some integration tests

## Continuous Integration

These tests can be integrated into CI/CD pipelines by running:
```bash
php tests/run_tests.php || exit 1
```