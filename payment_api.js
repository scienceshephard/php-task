/**
 * Squad Payment API Integration
 * This script handles payment initialization with Squad API
 */

const SQUAD_API_KEY = 'sandbox_sk_0f3c7f627c136a940a5861ea2ac0e75420bcb7f1c060';
const SQUAD_API_URL = 'https://sandbox-api.squadco.com/transactions/initiate';

/**
 * Generate a unique transaction reference
 */
function generateTransactionRef() {
    const timestamp = Date.now();
    const randomNum = Math.floor(Math.random() * 1000000);
    return `TXN_${timestamp}_${randomNum}`;
}

/**
 * Initialize Squad payment
 * @param {number} amount - Payment amount
 * @param {string} email - Customer email
 * @param {string} accountId - Account ID (hidden field)
 * @param {string} userId - User ID (hidden field)
 * @param {string} subscription - Subscription type
 */
async function initiateSquadPayment(amount, email, accountId, userId, subscription) {
    try {
        // Validate inputs
        if (!amount || amount <= 0) {
            throw new Error('Invalid amount');
        }
        if (!email || !validateEmail(email)) {
            throw new Error('Invalid email address');
        }
        if (!accountId || !userId) {
            throw new Error('Account ID and User ID are required');
        }

        // Generate transaction reference
        const transactionRef = generateTransactionRef();

        // Prepare request payload
        const payload = {
            amount: parseFloat(amount),
            email: email,
            currency: "NGN",
            initiate_type: "inline",
            transaction_ref: transactionRef,
            callback_url: window.location.origin + "/payment_callback.php"
        };

        // Show loading state
        showPaymentLoading(true);

        // Make API request
        const response = await fetch(SQUAD_API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${SQUAD_API_KEY}`
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        // Hide loading state
        showPaymentLoading(false);

        if (response.ok && data.success) {
            // Payment initiated successfully
            console.log('Payment initiated:', data);

            // Send payment details to database
            await savePaymentToDatabase({
                transaction_ref: transactionRef,
                account_id: accountId,
                user_id: userId,
                payment_method: 'squad_online',
                email: email,
                amount: amount,
                currency: 'NGN',
                subscription: subscription,
                payment_data: JSON.stringify(data.data)
            });

            // Redirect to payment page or open payment modal
            if (data.data.checkout_url) {
                window.location.href = data.data.checkout_url;
            } else {
                alert('Payment initiated but no checkout URL received');
            }

        } else {
            // Handle error
            console.error('Payment initiation failed:', data);
            alert('Payment initiation failed: ' + (data.message || 'Unknown error'));
        }

    } catch (error) {
        showPaymentLoading(false);
        console.error('Error initiating payment:', error);
        alert('Error: ' + error.message);
    }
}

/**
 * Save payment details to database
 */
async function savePaymentToDatabase(paymentData) {
    try {
        const response = await fetch('Api_online_paymentdet.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(paymentData)
        });

        const result = await response.json();

        if (!result.success) {
            console.error('Failed to save payment to database:', result.message);
            // Note: We don't stop the payment flow here
            // Just log the error for debugging
        } else {
            console.log('Payment details saved to database');
        }

        return result;

    } catch (error) {
        console.error('Error saving payment to database:', error);
        // Don't stop payment flow
    }
}

/**
 * Validate email format
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Show/hide loading state
 */
function showPaymentLoading(show) {
    const button = document.querySelector('#onlinpaymentdet');
    if (button) {
        if (show) {
            button.style.opacity = '0.5';
            button.style.pointerEvents = 'none';
            button.innerHTML += ' <span class="spinner-border spinner-border-sm" role="status"></span>';
        } else {
            button.style.opacity = '1';
            button.style.pointerEvents = 'auto';
            const spinner = button.querySelector('.spinner-border');
            if (spinner) spinner.remove();
        }
    }
}

/**
 * Handle online payment button click
 * This function should be called when user clicks on online payment
 */
function handleOnlinePayment() {
    // Get values from form
    const subscription = document.getElementById('Subscription').value;
    const amount = document.getElementById('amount').innerText.replace(/,/g, '');
    
    // Get email from user input (you may need to add an email input field)
    const email = prompt('Please enter your email address for payment confirmation:');
    
    if (!email) {
        alert('Email is required for online payment');
        return false;
    }

    // Get hidden values (you need to add these hidden inputs to your form)
    const accountId = document.getElementById('accountId')?.value || '';
    const userId = document.getElementById('userId')?.value || '';

    if (!subscription || !amount) {
        alert('Please select a subscription type first!');
        return false;
    }

    if (!accountId || !userId) {
        alert('Account information is missing. Please contact support.');
        return false;
    }

    // Initiate payment
    initiateSquadPayment(amount, email, accountId, userId, subscription);
    
    return false; // Prevent default action
}

// Export for use in payment.php
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initiateSquadPayment,
        handleOnlinePayment
    };
}