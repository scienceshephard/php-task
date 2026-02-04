<?php
/**
 * API Online Payment Details Handler
 * Processes payment data from Squad API and saves to database
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set headers for JSON response
header('Content-Type: application/json');

// Database configuration - UPDATE THESE WITH YOUR DATABASE CREDENTIALS
define('DB_HOST', 'localhost');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'your_database_name');

// Response array
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    // Connect to database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");

    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate input
    if (!$data) {
        throw new Exception("Invalid JSON data received");
    }

    // Extract and validate required fields
    $required_fields = ['transaction_ref', 'account_id', 'user_id', 'payment_method', 'email', 'amount', 'currency'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Missing required field: " . $field);
        }
    }

    // Extract data
    $transaction_ref = $data['transaction_ref'];
    $account_id = $data['account_id'];
    $user_id = $data['user_id'];
    $payment_method = $data['payment_method'];
    $email = $data['email'];
    $amount = floatval($data['amount']);
    $currency = $data['currency'];
    $subscription = isset($data['subscription']) ? $data['subscription'] : '';
    $payment_data = isset($data['payment_data']) ? $data['payment_data'] : '';

    // Generate unique transaction ID
    $transaction_id = generateTransactionId();

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address");
    }

    // Validate amount
    if ($amount <= 0) {
        throw new Exception("Invalid amount");
    }

    // Prepare SQL statement
    $sql = "INSERT INTO online_payments (
                transaction_id,
                transaction_ref,
                account_id,
                user_id,
                payment_method,
                email,
                amount,
                currency,
                subscription_type,
                payment_data,
                payment_status,
                created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssdsss",
        $transaction_id,
        $transaction_ref,
        $account_id,
        $user_id,
        $payment_method,
        $email,
        $amount,
        $currency,
        $subscription,
        $payment_data
    );

    // Execute statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Payment details saved successfully';
        $response['data'] = [
            'transaction_id' => $transaction_id,
            'transaction_ref' => $transaction_ref,
            'insert_id' => $stmt->insert_id
        ];

        // Log successful payment
        logPayment($conn, $transaction_id, 'Payment initiated', 'success');

    } else {
        throw new Exception("Failed to insert payment record: " . $stmt->error);
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();

    // Log error if connection exists
    if (isset($conn) && $conn->ping()) {
        logPayment($conn, $transaction_id ?? 'unknown', 'Error: ' . $e->getMessage(), 'error');
        $conn->close();
    }
}

// Send JSON response
echo json_encode($response);
exit;

/**
 * Generate unique transaction ID
 */
function generateTransactionId() {
    return 'TXN_' . date('YmdHis') . '_' . uniqid();
}

/**
 * Log payment activity
 */
function logPayment($conn, $transaction_id, $message, $status) {
    try {
        $sql = "INSERT INTO payment_logs (transaction_id, message, status, logged_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $transaction_id, $message, $status);
            $stmt->execute();
            $stmt->close();
        }
    } catch (Exception $e) {
        // Silently fail - logging shouldn't break the main flow
        error_log("Failed to log payment: " . $e->getMessage());
    }
}
?>