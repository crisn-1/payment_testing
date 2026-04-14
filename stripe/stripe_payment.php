<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

header('Content-Type: application/json');

try {
    // Get the posted data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $amount = floatval($input['amount']);
    $donorName = $input['donor_name'] ?? '';
    $donorEmail = $input['donor_email'] ?? '';
    
    // Validate amount
    if ($amount < 1) {
        throw new Exception('Amount must be at least $1');
    }
    
    // Validate required fields
    if (empty($donorName) || empty($donorEmail)) {
        throw new Exception('Name and email are required');
    }
    
    // Create a PaymentIntent
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount * 100, // Convert to cents
        'currency' => 'usd',
        'description' => 'Donation from ' . $donorName,
        'receipt_email' => $donorEmail,
        'metadata' => [
            'donor_name' => $donorName,
            'donor_email' => $donorEmail,
        ],
    ]);
    
    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
