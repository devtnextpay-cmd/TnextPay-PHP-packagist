# Quick Start Guide - Technonextpay PHP Plugin

## 🚀 Get Started in 5 Minutes

This guide shows you how to integrate Technonextpay payments into your PHP application quickly.

## Prerequisites

- PHP 7.4 or higher
- Web server (Apache/Nginx)
- SSL certificate (recommended for production)

## Step 1: Setup Environment

Create a `.env` file in your project root:

```bash
cp .env.example .env
```

Then edit the `.env` file with your actual merchant credentials:

```env
API_KEY=your_api_key
API_SECRET=your_api_sercret
MERCHANT_CODE=your_merchant_code
TechnonextPay_API_ENDPOINT=https://sandbox.technonextpay.com
SUCCESS_URL=https://yourdomain.com/success.php
FAILURE_URL=https://yourdomain.com/failure.php
CANCEL_URL=https://yourdomain.com/cancel.php
IPN_URL=https://yourdomain.com/ipn.php
LOG_LOCATION=./logs
CURLOPT_SSL_VERIFYPEER=1
```

## Step 2: Include Plugin Files

Download the plugin files and include them in your project:

```php
<?php
require_once 'src/TechnonextpayEnvReader.php';
require_once 'src/Technonextpay.php';
require_once 'src/PaymentRequest.php';
```

## Step 3: Create Payment Form

Create `payment_form.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Payment Form</title>
</head>
<body>
    <h1>Complete Your Payment</h1>
    <form action="process_payment.php" method="POST">
        <label>Amount:</label>
        <input type="number" name="payable_amount" step="0.01" min="0.01" required><br>

        <label>Currency:</label>
        <select name="currency_code" required>
            <option value="BDT">BDT - Bangladeshi Taka</option>
            <option value="USD">USD - US Dollar</option>
        </select><br>

        <label>Customer Name:</label>
        <input type="text" name="customer_name" required><br>

        <label>Email:</label>
        <input type="email" name="customer_email" required><br>

        <label>Phone:</label>
        <input type="text" name="contact_number" required><br>

        <label>Address:</label>
        <input type="text" name="customer_primaryAddress" required><br>

        <button type="submit">Pay Now</button>
    </form>
</body>
</html>
```

## Step 4: Process Payment

Create `process_payment.php`:

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

    // Validate inputs
    $amount = filter_input(INPUT_POST, 'payable_amount', FILTER_VALIDATE_FLOAT);
    if (!$amount || $amount <= 0) {
        throw new Exception('Invalid amount');
    }

    // Create payment request
    $request = new PaymentRequest();
    $request->payable_amount = $amount;
    $request->currency_code = $_POST['currency_code'];
    $request->customer_name = trim($_POST['customer_name']);
    $request->customer_email = filter_var($_POST['customer_email'], FILTER_VALIDATE_EMAIL);
    $request->contact_number = trim($_POST['contact_number']);
    $request->customer_primaryAddress = trim($_POST['customer_primaryAddress']);

    // Additional required fields
    $request->customer_city = 'Dhaka';
    $request->customer_state = 'Dhaka';
    $request->customer_postcode = '1200';
    $request->customer_country = 'Bangladesh';
    $request->preferred_channel = 'VISA';

    // Process payment
    $technonextpay = new Technonextpay($config);
    $response = $technonextpay->paymentOrder($request);

    // Redirect to payment gateway
    if (isset($response->gateway_page_url)) {
        header('Location: ' . $response->gateway_page_url);
        exit;
    } else {
        echo "Payment initiation failed. Please try again.";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Step 5: Handle Payment Results

Create `success.php`:

```php
<?php
require_once 'vendor/autoload.php';
use TechnonextPlugin\Technonextpay;
use TechnonextPlugin\TechnonextpayEnvReader;

require_once __DIR__ . '/src/Technonextpay.php';
require_once __DIR__ . '/src/TechnonextpayEnvReader.php';

$env = new TechnonextpayEnvReader(__DIR__ . '/_env');
$conf = $env->getConfig();

$sp_instance = new Technonextpay($conf);


$response_data = (object)array(
    'Status' => 'No data found'
);

if ($_REQUEST['orderId'])
{
  $payload = (object) array(
    'order_id' => trim($_REQUEST['orderId']),
    'order_tracking_id' =>  trim($_REQUEST['orderReference']),
    'merchant_code' =>  $conf->merchant_code,
    'amount' => trim($_REQUEST['amount'])
  );
  $response_data = json_decode(json_encode($sp_instance->verifyPayment($payload)));


echo "<h1>Payment Successful!</h1>";
echo "<p>Order ID: " . $response_data->order_id . "</p>";
echo "<p>Amount: " . $response_data->amount . "</p>";
// Update your database here
```

Create `failure.php`:

```php
<?php

echo "<h1>Payment Failed</h1>";
echo "<p>Please try again or contact support.</p>";
```

Create `cancel.php`:

```php
<?php
echo "<h1>Payment Cancelled</h1>";
echo "<p>You can try again when ready.</p>";
```

## Step 6: Test the Integration

1. Start your web server
2. Visit `payment_form.php`
3. Fill out the form and submit
4. Complete payment on the gateway
5. Check the success/failure pages

## 🎯 What Each Component Does

| Component | Purpose |
|-----------|---------|
| `TechnonextpayEnvReader` | Loads your API credentials securely |
| `TechnonextpayConfig` | Holds all configuration settings |
| `PaymentRequest` | Stores payment data in a structured way |
| `TechnonextpayValidation` | Checks that payment data is valid |
| `Technonextpay` | Handles communication with payment gateway |

## 🔧 Common Issues & Solutions

### "Class not found" errors
- Make sure all `require_once` statements point to correct file paths
- Check that files are in the `src/` directory

### "Validation failed"
- Ensure all required fields are filled
- Check email format and phone number (11 digits)

### "API connection failed"
- Verify your `.env` file has correct credentials
- Check that API endpoint URL is accessible

### Payment not processing
- Confirm merchant account is active
- Check payment gateway status

## 📚 Next Steps

- Read the full [Developer Guide](DEVELOPER_GUIDE.md) for advanced features
- Run `php tests/run_tests.php` to verify your setup
- Add error logging and database integration
- Implement IPN (Instant Payment Notification) handling

## 🆘 Need Help?

- Check the [Developer Guide](DEVELOPER_GUIDE.md) for detailed explanations
- Run the test suite: `php tests/run_tests.php`
- Review error logs in your configured log directory
- Contact Technonextpay support for API-specific issues

---

**Happy coding! 🎉**</content>
