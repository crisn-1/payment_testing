<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Stripe API Configuration
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY'));
define('STRIPE_PUBLISHABLE_KEY', getenv('STRIPE_PUBLISHABLE_KEY'));

// PayPal API Configuration
define('PAYPAL_CLIENT_ID', getenv('PAYPAL_CLIENT_ID'));
define('PAYPAL_CLIENT_SECRET', getenv('PAYPAL_CLIENT_SECRET'));
define('PAYPAL_MODE', getenv('PAYPAL_MODE')); // 'sandbox' for testing, 'live' for production

// Replace the above keys with your actual API keys
// Stripe: https://dashboard.stripe.com/test/apikeys
// PayPal: https://developer.paypal.com/dashboard/applications/sandbox
