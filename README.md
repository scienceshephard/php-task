# Squad Payment Integration - Altech School Management System

## Overview
This package integrates Squad payment gateway into the Altech School Management System, allowing users to pay for subscriptions online using cards and other payment methods.

## Files Included

### 1. **payment_api.js**
JavaScript file that handles the frontend payment integration with Squad API.
- Initiates payment requests
- Validates user input
- Sends payment data to backend
- Handles payment responses

### 2. **Api_online_paymentdet.php**
PHP backend file that receives payment data and saves it to the database.
- Receives payment details from frontend
- Validates input data
- Generates unique transaction IDs
- Stores payment records in database
- Returns JSON responses

### 3. **payment_callback.php**
Handles callback responses from Squad payment gateway.
- Verifies transactions with Squad API
- Updates payment status in database
- Redirects users based on payment outcome
- Logs all activities

### 4. **payment_updated.php**
Updated version of the original payment.php with integrated online payment functionality.
- Includes both online and bank transfer payment options
- Has email input field for online payments
- Calculates subscription amounts dynamically
- Integrates with payment_api.js

### 5. **database_schema.sql**
SQL file containing all necessary database tables.
- online_payments table
- payment_logs table
- payment_webhooks table
- subscription_activations table

## Testing the Integration

### Using Sandbox Mode

The integration is currently set to use Squad's sandbox environment. Use these test credentials:

**Test Card Details:**
- Card Number: 4111 1111 1111 1111
- Expiry: Any future date (e.g., 12/25)
- CVV: Any 3 digits (e.g., 123)
- PIN: 1234

### Test Flow

1. Go to your payment page
2. Click "make your payment"
3. Select "online payment"
4. Choose a subscription type
5. Enter your email address
6. Click "Pay Now with Card"
7. You should be redirected to Squad's payment page
8. Use the test card details above
9. Complete the payment
10. You should be redirected back to your success page

## Security Considerations

1. **HTTPS Required**: Always use HTTPS in production
2. **API Key Security**: Never expose API keys in client-side code (currently safe as it's in JS for demo purposes, but consider moving to server-side)
3. **Input Validation**: All inputs are validated before processing
4. **SQL Injection Prevention**: Prepared statements are used throughout
5. **Session Security**: Use secure session handling in production

## Troubleshooting

### Payment Not Initiating
- Check browser console for JavaScript errors
- Verify API key is correct
- Ensure all required fields are filled
- Check network tab for API request/response

### Database Errors
- Verify database credentials are correct
- Ensure all tables exist
- Check user has proper permissions
- Look at payment_logs table for errors

### Callback Not Working
- Verify callback URL is accessible
- Check that payment_callback.php has correct database credentials
- Look at server error logs
- Ensure CURL is enabled on server

### Amount Not Calculating
- Check that amountCharge and studentCount variables have values
- Verify subscription is selected
- Check browser console for errors

## Support

For issues or questions:
- Contact Altech Support: https://wa.link/ch2f9b
- Squad Documentation: https://docs.squadco.com
- Check payment_logs table for detailed error messages

## File Permissions

Set appropriate permissions:
```bash
chmod 644 payment_api.js
chmod 600 Api_online_paymentdet.php
chmod 600 payment_callback.php
chmod 644 payment_updated.php
```

## License

This integration is proprietary to Altech Services Ltd.

---