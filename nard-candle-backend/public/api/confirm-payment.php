<?php
// Simple standalone payment confirmation handler
$data = $_GET;

// Create log directory if it doesn't exist
$logDir = __DIR__ . '/../../storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

// Log the data to our own log file
file_put_contents(
    $logDir . '/payment-direct.log', 
    date('Y-m-d H:i:s') . ' - ' . json_encode($data) . "\n", 
    FILE_APPEND
);

// Get transaction reference
$txRef = isset($data['tx_ref']) ? $data['tx_ref'] : 'unknown';
$status = isset($data['status']) ? $data['status'] : 'unknown';

// HTML response
echo '<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
    <style>
        body {font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;}
        h1 {color: #333;}
        .success {color: green;}
        .error {color: red;}
        pre {background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto;}
    </style>
</head>
<body>
    <h1>Payment Confirmation</h1>';

if ($status == 'success') {
    echo '<h2 class="success">Payment Successful!</h2>';
} else {
    echo '<h2 class="error">Payment Failed or Unknown</h2>';
}

echo '<p><strong>Transaction Reference:</strong> ' . htmlspecialchars($txRef) . '</p>';
echo '<p><strong>Status:</strong> ' . htmlspecialchars($status) . '</p>';
echo '<h3>All Data Received:</h3>';
echo '<pre>' . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . '</pre>';
echo '<p><a href="/">Return to Home</a></p>';
echo '</body></html>'; 