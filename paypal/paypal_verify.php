<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Get the posted data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $orderID = $input['orderID'] ?? '';
    $donorName = $input['donor_name'] ?? 'Anonymous';
    $donorEmail = $input['donor_email'] ?? '';
    
    if (empty($orderID)) {
        throw new Exception('Order ID is required');
    }
    
    // Get PayPal access token
    $ch = curl_init();
    $clientId = PAYPAL_CLIENT_ID;
    $secret = PAYPAL_CLIENT_SECRET;
    $baseUrl = PAYPAL_MODE === 'live' 
        ? 'https://api-m.paypal.com' 
        : 'https://api-m.sandbox.paypal.com';
    
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $tokenResponse = curl_exec($ch);
    $tokenData = json_decode($tokenResponse, true);
    $accessToken = $tokenData['access_token'] ?? null;
    
    if (!$accessToken) {
        throw new Exception('Failed to get PayPal access token');
    }
    
    // Verify the order
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v2/checkout/orders/' . $orderID);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    
    $orderResponse = curl_exec($ch);
    $orderData = json_decode($orderResponse, true);
    curl_close($ch);
    
    // Check if order is completed
    if ($orderData['status'] !== 'COMPLETED') {
        throw new Exception('Order not completed');
    }
    
    // Log the donation (you can save to database here)
    $amount = $orderData['purchase_units'][0]['amount']['value'];
    error_log("PayPal Donation: $amount from $donorName ($donorEmail) - Order: $orderID");
    
    echo json_encode([
        'success' => true,
        'orderID' => $orderID,
        'amount' => $amount
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
