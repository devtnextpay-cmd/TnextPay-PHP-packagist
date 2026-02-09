# Technonextpay PHP Plugin - Developer Guide

## 🎯 Overview

The Technonextpay PHP Plugin is a comprehensive payment gateway integration solution that enables secure online payment processing in PHP applications. This guide explains how each component works and how to integrate the plugin into your application.

## 📋 Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Core Components](#core-components)
3. [Payment Flow](#payment-flow)
4. [Configuration](#configuration)
5. [Integration Steps](#integration-steps)
6. [API Reference](#api-reference)
7. [Error Handling](#error-handling)
8. [Testing](#testing)
9. [Best Practices](#best-practices)

## 🏗️ Architecture Overview

The plugin follows a modular architecture with clear separation of concerns:

```
├── Configuration Layer (TechnonextpayEnvReader, TechnonextpayConfig)
├── Data Layer (PaymentRequest)
├── Validation Layer (TechnonextpayValidation)
├── Service Layer (Technonextpay)
└── Presentation Layer (HTML forms, callbacks)
```

### Why This Architecture?

- **Separation of Concerns**: Each class has a single responsibility
- **Testability**: Modular design enables comprehensive unit testing
- **Maintainability**: Changes to one component don't affect others
- **Security**: Input validation and secure communication layers

### Component Relationships

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Technonextpay   │────│ PaymentRequest  │────│   Your Data     │
│ Service Class   │    │   (DTO)         │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                        │
         │                        │
         ▼                        ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Technonextpay   │────│ Technonextpay   │────│   API Calls     │
│ Validation      │    │ Config          │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         ▲                        ▲
         │                        │
         │                        │
┌─────────────────┐    ┌─────────────────┐
│ Technonextpay   │────│   .env File     │
│ EnvReader       │    │                 │
└─────────────────┘    └─────────────────┘
```

## 🔧 Core Components

### 1. PaymentRequest - Data Transfer Object

**Purpose**: Holds all payment-related data in a structured format.

**Key Properties**:
- `currency_code`: Payment currency (BDT, USD, EUR, etc.)
- `payable_amount`: Amount to be charged
- `customer_name`: Customer's full name
- `customer_email`: Customer's email address
- `contact_number`: Customer's phone number
- `customer_primaryAddress`: Billing address
- `preferred_channel`: Payment method (VISA, MASTERCARD, BKASH, etc.)

**Why it exists**: Standardizes data format across the entire payment flow, making it easy to validate and transmit payment information.

### 2. TechnonextpayConfig - Configuration Container

**Purpose**: Stores all configuration settings and credentials.

**Key Settings**:
- API credentials (username, password, merchant code)
- Endpoint URLs (success, failure, cancel, IPN)
- SSL and security settings
- Logging configuration

**Why it exists**: Centralizes configuration management, making it easy to switch between development and production environments.

### 3. TechnonextpayEnvReader - Environment Loader

**Purpose**: Loads configuration from environment variables or .env files.

**Key Features**:
- Reads `.env` files securely
- Populates configuration objects
- Handles missing environment variables

**Why it exists**: Provides secure credential management without hardcoding sensitive information in source code.

### 4. TechnonextpayValidation - Input Validator

**Purpose**: Validates all payment data before processing.

**Validation Rules**:
- Required field checking
- Email format validation
- Phone number format validation (11 digits)
- Amount validation (must be > 0)

**Why it exists**: Prevents invalid payments and provides immediate feedback on data issues.

### 5. Technonextpay - Main Service Class

**Purpose**: Orchestrates the entire payment process.

**Key Methods**:
- `paymentOrder()`: Initiates payment transactions
- `verifyPayment()`: Verifies completed payments
- `prepareTransactionPayload()`: Formats data for API
- `getHttpResponse()`: Handles API communication

**Why it exists**: Provides a clean API for payment operations while managing all the complex logic internally.

## 💰 Payment Flow

### Visual Flow Diagram

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   Customer      │    │   Your App       │    │  Technonextpay  │
│   Browses       │───▶│   Shows          │───▶│   Gateway       │
│   Products      │    │   Payment Form   │    │   Processes     │
└─────────────────┘    └──────────────────┘    │   Payment       │
                                               └─────────────────┘
                                                        │
┌─────────────────┐    ┌──────────────────┐             │
│   Customer      │◀───│   Redirects to   │◀────────────┘
│   Completes     │    │   Success/Fail   │
│   Payment       │    │   Pages          │
└─────────────────┘    └──────────────────┘
```

### Detailed Step-by-Step Flow

### Step 1: Form Submission
```
User fills payment form → technonextpay_payment.php
```

**Purpose**: Collect payment details from customer
- Customer enters amount, selects payment method
- Form validates basic input (HTML5 validation)
- Submits to processing script

### Step 2: Input Processing & Validation
```php
// Load configuration
$env = new TechnonextpayEnvReader(__DIR__ . '/_env');
$config = $env->getConfig();

// Create payment request
$request = new PaymentRequest();
// ... populate from $_POST data

// Validate thoroughly
$validator = new TechnonextpayValidation();
if (!$validator->Validation($payload)) {
    die("Validation failed");
}
```

**Purpose**: Ensure data integrity before API calls
- Sanitize all user inputs
- Validate business rules
- Prevent invalid API requests

### Step 3: Payment Initiation
```php
$technonextpay = new Technonextpay($config);
$response = $technonextpay->paymentOrder($request);
```

**Purpose**: Send payment request to Technonextpay
- Prepare JSON payload
- Make secure API call
- Redirect to payment gateway

### Step 4: Gateway Processing
```
Technonextpay Gateway → Customer completes payment
```

**Purpose**: Handle actual payment processing
- Customer interacts with payment gateway
- Gateway processes card/mobile payment
- Returns success/failure status

### Step 5: Callback Handling
```php
// return.php - Handle success/failure redirects
// ipn.php - Handle asynchronous notifications
```

**Purpose**: Process payment completion
- Update order status in database
- Send confirmation emails
- Handle failed payments

## ⚙️ Configuration

### Environment Setup

Create a `.env` file in your project root:

```bash
cp .env.example .env
```

Then edit the `.env` file with your actual merchant credentials:

```env
API_KEY=your_merchant_api_key
API_SECRET=your_merchant_api_secret
MERCHANT_CODE=your_merchant_code
TechnonextPay_API_ENDPOINT=https://api.technonextpay.com
SUCCESS_URL=https://yourdomain.com/success
FAILURE_URL=https://yourdomain.com/failure
CANCEL_URL=https://yourdomain.com/cancel
IPN_URL=https://yourdomain.com/ipn
LOG_LOCATION=/var/log/technonextpay
CURLOPT_SSL_VERIFYPEER=1
```

### Loading Configuration

```php
use TechnonextPlugin\TechnonextpayEnvReader;

// Load from .env file
$env = new TechnonextpayEnvReader(__DIR__ . '/.env');
$config = $env->getConfig();

// Use configuration
$technonextpay = new Technonextpay($config);
```

## 🚀 Integration Steps

### Step 1: Install Dependencies

```bash
composer require technonext/technonext-plugin-php
```

Or manually include the plugin files:

```php
require_once 'vendor/autoload.php';

require_once 'src/TechnonextpayEnvReader.php';
require_once 'src/Technonextpay.php';
require_once 'src/PaymentRequest.php';
```

### Step 2: Create Payment Form

```html
<form action="process_payment.php" method="POST">
    <input type="number" name="payable_amount" required>
    <select name="currency_code">
        <option value="BDT">BDT</option>
        <option value="USD">USD</option>
    </select>
    <input type="text" name="customer_name" required>
    <input type="email" name="customer_email" required>
    <input type="text" name="contact_number" required>
    <button type="submit">Pay Now</button>
</form>
```

### Step 3: Process Payment

```php
<?php
require_once 'vendor/autoload.php';

use TechnonextPlugin\TechnonextpayEnvReader;
use TechnonextPlugin\Technonextpay;
use TechnonextPlugin\PaymentRequest;

try {
    // Load configuration
    $env = new TechnonextpayEnvReader(__DIR__ . '/.env');
    $config = $env->getConfig();

    // Create payment request
    $request = new PaymentRequest();
    $request->payable_amount = $_POST['payable_amount'];
    $request->currency_code = $_POST['currency_code'];
    $request->customer_name = $_POST['customer_name'];
    $request->customer_email = $_POST['customer_email'];
    $request->contact_number = $_POST['contact_number'];
    // ... set other required fields

    // Process payment
    $technonextpay = new Technonextpay($config);
    $response = $technonextpay->paymentOrder($request);

    // Redirect to gateway or handle response
    if (isset($response->gateway_page_url)) {
        header('Location: ' . $response->gateway_page_url);
    }

} catch (Exception $e) {
    echo "Payment failed: " . $e->getMessage();
}
```

### Step 4: Handle Callbacks

**Success/Cancel/Failure Redirects (return.php):**

```php
<?php
require_once 'vendor/autoload.php';

use TechnonextPlugin\TechnonextpayEnvReader;
use TechnonextPlugin\Technonextpay;

$env = new TechnonextpayEnvReader(__DIR__ . '/.env');
$config = $env->getConfig();
$technonextpay = new Technonextpay($config);

// Verify payment status
$verification = $technonextpay->verifyPayment([
    'paymentOrderId' => $_REQUEST['orderReference']
]);

if ($verification->status === 'APPROVED') {
    // Payment successful - update database, send email, etc.
    echo "Payment successful!";
} else {
    // Payment failed
    echo "Payment failed!";
}
```

**IPN Handler (ipn.php):**

```php
<?php
// Handle asynchronous payment notifications
// Update order status based on IPN data
// This is called by Technonextpay servers
```

## 📚 API Reference

### Technonextpay Class

#### `paymentOrder(PaymentRequest $payload)`

Initiates a payment transaction.

**Parameters:**
- `$payload`: PaymentRequest object with transaction details

**Returns:** API response object or redirects to gateway

**Throws:** TechnonextpayException on validation or API errors

#### `verifyPayment(array $payload)`

Verifies the status of a completed payment.

**Parameters:**
- `$payload`: Array with order_id, order_tracking_id, merchant_code

**Returns:** Verification response from API

#### `prepareTransactionPayload(PaymentRequest $payload)`

Internal method that formats payment data for API submission.

#### `getHttpResponse($url, $method, $payload, $headers)`

Internal method for making HTTP requests to the API.

### PaymentRequest Class

All properties are public and correspond to payment fields:

```php
$request = new PaymentRequest();
$request->payable_amount = 100.50;
$request->currency_code = 'BDT';
$request->customer_name = 'John Doe';
// ... etc
```

### TechnonextpayValidation Class

#### `Validation($payload)`

Validates complete payment payload.

**Returns:** `true` if valid, `false` if invalid

#### `emptyCheck($field, $value)`

Checks if a field is empty or null.

#### `emailCheck($email)`

Validates email format.

#### `phoneCheck($phone)`

Validates phone number (11 digits).

## 🚨 Error Handling

### Exception Types

- **TechnonextpayException**: Custom exception with detailed error information
- **Validation Errors**: Returned as `false` from validation methods
- **API Errors**: HTTP errors from payment gateway
- **Network Errors**: Connection issues

### Error Handling Pattern

```php
try {
    $response = $technonextpay->paymentOrder($request);
} catch (TechnonextpayException $e) {
    // Log error
    error_log("Payment error: " . $e->getMessage());

    // Show user-friendly message
    echo "Payment processing failed. Please try again.";
} catch (Exception $e) {
    // Handle unexpected errors
    error_log("Unexpected error: " . $e->getMessage());
    echo "An unexpected error occurred.";
}
```

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php tests/run_tests.php

# Run specific test
php tests/PaymentRequestTest.php
```

### Test Coverage

- Unit tests for all classes
- Integration tests for payment flow
- Validation testing
- Error condition testing

## ✅ Best Practices

### Security
- ✅ Use environment variables for credentials
- ✅ Enable SSL verification in production
- ✅ Validate all user inputs
- ✅ Use HTTPS for all payment pages
- ❌ Never log sensitive payment data

### Performance
- ✅ Cache configuration objects
- ✅ Use appropriate timeouts
- ✅ Handle network failures gracefully
- ✅ Log errors without exposing sensitive data

### User Experience
- ✅ Provide clear error messages
- ✅ Show payment progress indicators
- ✅ Support multiple payment methods
- ✅ Handle mobile-responsive design

### Development
- ✅ Write unit tests for new features
- ✅ Follow PSR coding standards
- ✅ Document all public methods
- ✅ Use meaningful variable names

## 🔍 Troubleshooting

### Common Issues

**"Validation failed"**
- Check that all required fields are provided
- Verify email and phone formats
- Ensure amount is greater than 0

**"API connection failed"**
- Verify API endpoint URL
- Check network connectivity
- Confirm SSL settings

**"Invalid credentials"**
- Verify username/password in environment
- Check merchant code
- Ensure .env file is readable

### Debug Mode

Enable detailed logging:

```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

Check logs in the configured log directory for detailed error information.

## 📞 Support

For technical support:
- Check the [GitHub repository](https://github.com/tnextpay-plugins/tnextpay-plugin-usage-examples)
- Review the [API documentation](#)
- Contact Technonextpay support team

## 📝 License

This plugin is licensed under the MIT License. See LICENSE file for details.

---

**Last Updated:** January 2026
**Version:** 1.0.0</content>
