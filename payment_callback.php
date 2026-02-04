<?php
/**
 * Payment Callback Handler
 * This file handles the callback from Squad payment gateway
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration - UPDATE THESE WITH YOUR DATABASE CREDENTIALS
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Squad API configuration
define('SQUAD_API_KEY', 'sandbox_sk_0f3c7f627c136a940a5861ea2ac0e75420bcb7f1c060');
define('SQUAD_VERIFY_URL', 'https://sandbox-api.squadco.com/transactions/verify/');

try {
    // Get transaction reference from URL parameter
    $transaction_ref = isset($_GET['transaction_ref']) ? $_GET['transaction_ref'] : '';
    
    if (empty($transaction_ref)) {
        throw new Exception("No transaction reference provided");
    }

    // Verify the transaction with Squad API
    $verification = verifySquadTransaction($transaction_ref);

    if (!$verification['success']) {
        throw new Exception("Transaction verification failed: " . $verification['message']);
    }

    // Connect to database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");

    // Update payment status in database
    $payment_status = $verification['data']['transaction_status'];
    $gateway_ref = $verification['data']['gateway_transaction_ref'];
    $paid_at = $verification['data']['transaction_date'];

    $sql = "UPDATE online_payments SET 
                payment_status = ?,
                gateway_ref = ?,
                paid_at = ?,
                payment_data = ?,
                updated_at = NOW()
            WHERE transaction_ref = ?";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Failed to prepare update statement: " . $conn->error);
    }

    $payment_data_json = json_encode($verification['data']);
    
    $stmt->bind_param(
        "sssss",
        $payment_status,
        $gateway_ref,
        $paid_at,
        $payment_data_json,
        $transaction_ref
    );

    if (!$stmt->execute()) {
        throw new Exception("Failed to update payment status: " . $stmt->error);
    }

    $stmt->close();

    // Log the callback
    logCallback($conn, $transaction_ref, 'Payment callback processed', $payment_status);

    // Redirect based on payment status
    if (strtolower($payment_status) === 'success') {
        // Payment successful - redirect to success page
        $_SESSION['payment_success'] = true;
        $_SESSION['transaction_ref'] = $transaction_ref;
        $_SESSION['payment_message'] = "Payment successful! Your subscription has been activated.";
        
        header("Location: payment_success.php");
        exit;
    } else {
        // Payment failed - redirect to failure page
        $_SESSION['payment_error'] = true;
        $_SESSION['transaction_ref'] = $transaction_ref;
        $_SESSION['payment_message'] = "Payment failed. Please try again or contact support.";
        
        header("Location: payment_failed.php");
        exit;
    }

    $conn->close();

} catch (Exception $e) {
    // Log error
    error_log("Payment callback error: " . $e->getMessage());
    
    // Redirect to error page
    $_SESSION['payment_error'] = true;
    $_SESSION['payment_message'] = "An error occurred: " . $e->getMessage();
    
    header("Location: payment_failed.php");
    exit;
}

/**
 * Verify transaction with Squad API
 */
function verifySquadTransaction($transaction_ref) {
    $url = SQUAD_VERIFY_URL . $transaction_ref;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . SQUAD_API_KEY,
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) {
        return [
            'success' => false,
            'message' => 'API request failed with status code: ' . $http_code,
            'data' => null
        ];
    }
    
    $data = json_decode($response, true);
    
    if (!$data || !isset($data['data'])) {
        return [
            'success' => false,
            'message' => 'Invalid API response',
            'data' => null
        ];
    }
    
    return [
        'success' => true,
        'message' => 'Transaction verified successfully',
        'data' => $data['data']
    ];
}

/**
 * Log callback activity
 */
function logCallback($conn, $transaction_ref, $message, $status) {
    try {
        $sql = "INSERT INTO payment_logs (transaction_id, message, status, logged_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $transaction_ref, $message, $status);
            $stmt->execute();
            $stmt->close();
        }
    } catch (Exception $e) {
        error_log("Failed to log callback: " . $e->getMessage());
    }
}
?>