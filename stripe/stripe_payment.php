<?php
require_once '../config.php';
require_once '../vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

header('Content-Type: application/json');

try {
    // Get the posted data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $amount = floatval($input['amount']);
    
    // Validate amount
    if ($amount < 1) {
        throw new Exception('Amount must be at least $1');
    }
    
    // Create a PaymentIntent
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount * 100, // Convert to cents
        'currency' => 'usd',
        'description' => 'Donation',
    ]);
    
    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
