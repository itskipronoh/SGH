# SMS Booking System Setup Instructions

## Overview
This SMS booking system sends confirmation messages to both the client and the salon owner (+254782606380) when an appointment is booked.

## Features
- ✅ Instant SMS confirmation to client
- ✅ Booking notification to owner
- ✅ Beautiful booking success popup
- ✅ Automatic form reset after booking
- ✅ Booking log for record keeping
- ✅ Fallback notifications in browser console

## Setup Instructions

### Option 1: Africa's Talking (Recommended for Kenya)

1. **Sign up for Africa's Talking**
   - Go to https://africastalking.com
   - Create an account
   - Get your API credentials

2. **Configure the SMS service**
   - Open `send_booking_sms.php`
   - Replace `YOUR_ACTUAL_API_KEY_HERE` with your actual API key
   - Replace `sandbox` with your actual username for production

3. **Update API endpoint**
   - For production: Change URL to `https://api.africastalking.com/version1/messaging`
   - For testing: Keep `https://api.sandbox.africastalking.com/version1/messaging`

### Option 2: Twilio (Alternative)

1. **Sign up for Twilio**
   - Go to https://twilio.com
   - Create an account and get a phone number

2. **Use Twilio function**
   - Uncomment the `sendSMSWithTwilio` function in `send_booking_sms.php`
   - Add your Twilio credentials
   - Update the main SMS function to use Twilio

### Option 3: Local Testing (Demo Mode)

For testing without SMS API:
- The system will log messages to browser console
- Check `booking_log.txt` for booking records
- Browser notifications will show (if permitted)

## File Structure

```
sophys-glam-hub/
├── appointment.html (Updated with SMS functionality)
├── send_booking_sms.php (SMS backend handler)
├── booking_log.txt (Auto-generated booking log)
└── sms_setup_instructions.md (This file)
```

## Owner Phone Number

The system is configured to send all booking notifications to:
**+254782606380**

To change this, update the `$ownerPhone` variable in `send_booking_sms.php`.

## SMS Message Templates

### Client Message Format:
```
🌟 BOOKING CONFIRMED! 🌟

Hello [Client Name]!

Your appointment at Sophy's Glam Hub has been successfully booked:

📅 Date: [Date]
⏰ Time: [Time]  
💄 Service: [Service]
💰 Price: [Price]
⏱️ Duration: [Duration]

📍 Location: Sophy's Glam Hub, Nairobi

We're excited to pamper you! Please arrive 10 minutes early.

For any changes, call us at +254782606380

Thank you for choosing Sophy's Glam Hub! ✨
```

### Owner Message Format:
```
🔔 NEW BOOKING ALERT! 🔔

Client: [Name]
Phone: [Phone]
Service: [Service]
Price: [Price]
Date: [Date]
Time: [Time]
Duration: [Duration]
Professional: [Professional preference]
Notes: [Any notes]

Please confirm this booking with the client.
```

## Testing the System

1. **Open the appointment page**
2. **Fill out a booking form**
3. **Click "Book Appointment"**
4. **Check for:**
   - Success popup appears
   - Console logs show SMS details
   - `booking_log.txt` file created with entry
   - Browser notification (if enabled)

## Production Deployment

1. **Get SMS API credentials**
2. **Update API keys in send_booking_sms.php**
3. **Test with real phone numbers**
4. **Monitor `booking_log.txt` for booking records**
5. **Set up error logging and monitoring**

## Security Notes

- Keep API keys secure and never commit them to version control
- Use environment variables for production credentials
- Implement rate limiting to prevent SMS spam
- Validate and sanitize all input data
- Use HTTPS for all API communications

## Troubleshooting

### Common Issues:

1. **SMS not sending**
   - Check API credentials
   - Verify phone number format (+254...)
   - Check API endpoint URL
   - Review error logs

2. **PHP errors**
   - Ensure PHP cURL extension is enabled
   - Check file permissions for `booking_log.txt`
   - Verify JSON input format

3. **JavaScript errors**
   - Check browser console for errors
   - Ensure form fields have correct IDs
   - Verify fetch API support

## Support

For technical support:
- Check booking_log.txt for error details
- Review browser console for JavaScript errors
- Test SMS API with simple curl commands
- Contact your SMS provider for API issues

## Costs

**Africa's Talking SMS Rates (Kenya):**
- Local SMS: ~KES 0.80 per message
- International: Varies by destination

**Twilio SMS Rates:**
- Varies by country and volume
- Check Twilio pricing page for current rates

## Next Steps

1. Set up SMS API credentials
2. Test booking system thoroughly
3. Train staff on booking confirmation process
4. Monitor booking logs regularly
5. Consider adding email notifications as backup
