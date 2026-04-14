<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

header('Content-Type: application/json');

try {
    // Get the posted data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $amount = floatval($input['amount']);
    $donorName = $input['donor_name'] ?? 'Anonymous';
    $donorEmail = $input['donor_email'] ?? '';
    
    // Validate amount
    if ($amount < 1) {
        throw new Exception('Amount must be at least $1');
    }
    
    // Create Stripe Checkout Session
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $amount * 100, // Convert to cents
                'product_data' => [
                    'name' => 'Donation',
                    'description' => 'Thank you for your generous donation!',
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/success.php?session_id={CHECKOUT_SESSION_ID}",
        'cancel_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/donate.php",
        'customer_email' => $donorEmail ?: null,
        'metadata' => [
            'donor_name' => $donorName,
        ],
    ]);
    
    echo json_encode(['id' => $checkout_session->id]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
