CREATE TABLE IF NOT EXISTS `online_payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaction_id` VARCHAR(100) UNIQUE NOT NULL COMMENT 'System generated transaction ID',
  `transaction_ref` VARCHAR(100) NOT NULL COMMENT 'Squad API transaction reference',
  `gateway_ref` VARCHAR(100) DEFAULT NULL COMMENT 'Payment gateway reference from Squad',
  `account_id` VARCHAR(50) NOT NULL COMMENT 'Account/Organization ID',
  `user_id` VARCHAR(50) NOT NULL COMMENT 'User ID who made the payment',
  `payment_method` VARCHAR(50) NOT NULL COMMENT 'Payment method (e.g., squad_online, card, bank_transfer)',
  `email` VARCHAR(255) NOT NULL COMMENT 'Customer email address',
  `amount` DECIMAL(15,2) NOT NULL COMMENT 'Payment amount',
  `currency` VARCHAR(10) NOT NULL DEFAULT 'NGN' COMMENT 'Currency code (NGN, USD, etc.)',
  `subscription_type` VARCHAR(50) DEFAULT NULL COMMENT 'Subscription duration (6 months, 1 year, etc.)',
  `payment_data` TEXT DEFAULT NULL COMMENT 'JSON data from payment gateway',
  `payment_status` ENUM('pending', 'success', 'failed', 'cancelled') DEFAULT 'pending' COMMENT 'Payment status',
  `paid_at` DATETIME DEFAULT NULL COMMENT 'When payment was completed',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation time',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update time',
  
  -- Indexes for better query performance
  INDEX `idx_transaction_ref` (`transaction_ref`),
  INDEX `idx_gateway_ref` (`gateway_ref`),
  INDEX `idx_account_id` (`account_id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_payment_status` (`payment_status`),
  INDEX `idx_email` (`email`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores online payment transactions';

-- Payment logs table for tracking all payment activities
CREATE TABLE IF NOT EXISTS `payment_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaction_id` VARCHAR(100) NOT NULL COMMENT 'Related transaction ID',
  `message` TEXT DEFAULT NULL COMMENT 'Log message',
  `status` VARCHAR(50) DEFAULT NULL COMMENT 'Log status (success, error, info, warning)',
  `ip_address` VARCHAR(45) DEFAULT NULL COMMENT 'IP address of user',
  `user_agent` TEXT DEFAULT NULL COMMENT 'Browser user agent',
  `logged_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When log was created',
  
  -- Index for faster lookups
  INDEX `idx_transaction_id` (`transaction_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_logged_at` (`logged_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Logs all payment-related activities';

-- Payment webhooks table (for Squad webhook notifications)
CREATE TABLE IF NOT EXISTS `payment_webhooks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_type` VARCHAR(100) NOT NULL COMMENT 'Webhook event type',
  `transaction_ref` VARCHAR(100) DEFAULT NULL COMMENT 'Transaction reference',
  `payload` TEXT NOT NULL COMMENT 'Full webhook payload (JSON)',
  `processed` TINYINT(1) DEFAULT 0 COMMENT 'Whether webhook has been processed',
  `processed_at` DATETIME DEFAULT NULL COMMENT 'When webhook was processed',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When webhook was received',
  
  INDEX `idx_transaction_ref` (`transaction_ref`),
  INDEX `idx_processed` (`processed`),
  INDEX `idx_event_type` (`event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores webhook notifications from payment gateway';

-- Subscription activations table
CREATE TABLE IF NOT EXISTS `subscription_activations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `account_id` VARCHAR(50) NOT NULL COMMENT 'Account/Organization ID',
  `user_id` VARCHAR(50) NOT NULL COMMENT 'User ID',
  `transaction_id` VARCHAR(100) NOT NULL COMMENT 'Related payment transaction ID',
  `subscription_type` VARCHAR(50) NOT NULL COMMENT 'Type of subscription',
  `start_date` DATE NOT NULL COMMENT 'Subscription start date',
  `end_date` DATE NOT NULL COMMENT 'Subscription end date',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT 'Whether subscription is currently active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX `idx_account_id` (`account_id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_transaction_id` (`transaction_id`),
  INDEX `idx_is_active` (`is_active`),
  INDEX `idx_end_date` (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks subscription activations and renewals';
