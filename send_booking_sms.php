<?php
// SMS Booking Notification System for Sophy's Glam Hub
// This file handles sending SMS notifications to both client and owner

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get booking data from POST request
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// Extract booking information
$clientName = $input['clientName'] ?? '';
$clientPhone = $input['clientPhone'] ?? '';
$serviceName = $input['serviceName'] ?? 'Service consultation';
$price = $input['price'] ?? 'To be confirmed';
$duration = $input['duration'] ?? 'To be confirmed';
$date = $input['date'] ?? '';
$time = $input['time'] ?? '';
$professional = $input['professional'] ?? 'Any available';
$notes = $input['notes'] ?? '';

// Owner's phone number
$ownerPhone = '+254782606380';

// Format date for display
$formattedDate = date('l, F j, Y', strtotime($date));

// Prepare messages
$clientMessage = "🌟 BOOKING CONFIRMED! 🌟\n\n" .
    "Hello {$clientName}!\n\n" .
    "Your appointment at Sophy's Glam Hub has been successfully booked:\n\n" .
    "📅 Date: {$formattedDate}\n" .
    "⏰ Time: {$time}\n" .
    "💄 Service: {$serviceName}\n" .
    "💰 Price: {$price}\n" .
    "⏱️ Duration: {$duration}\n\n" .
    "📍 Location: Sophy's Glam Hub, Nairobi\n\n" .
    "We're excited to pamper you! Please arrive 10 minutes early.\n\n" .
    "For any changes, call us at {$ownerPhone}\n\n" .
    "Thank you for choosing Sophy's Glam Hub! ✨";

$ownerMessage = "🔔 NEW BOOKING ALERT! 🔔\n\n" .
    "Client: {$clientName}\n" .
    "Phone: {$clientPhone}\n" .
    "Service: {$serviceName}\n" .
    "Price: {$price}\n" .
    "Date: {$formattedDate}\n" .
    "Time: {$time}\n" .
    "Duration: {$duration}\n" .
    "Professional: " . ($professional === 'any' ? 'Any available' : $professional) . "\n" .
    ($notes ? "Notes: {$notes}\n" : '') .
    "\nPlease confirm this booking with the client.";

// Initialize results
$results = [];

// Send SMS to client
if (!empty($clientPhone)) {
    $clientResult = sendSMS($clientPhone, $clientMessage, 'SophysGlam');
    $results['client'] = $clientResult;
}

// Send SMS to owner
$ownerResult = sendSMS($ownerPhone, $ownerMessage, 'SophysGlam');
$results['owner'] = $ownerResult;

// Return results
echo json_encode([
    'success' => true,
    'message' => 'Booking notifications sent successfully',
    'results' => $results,
    'booking_details' => [
        'client' => $clientName,
        'service' => $serviceName,
        'date' => $formattedDate,
        'time' => $time
    ]
]);

/**
 * Send SMS using Africa's Talking API
 * 
 * @param string $phoneNumber The recipient's phone number
 * @param string $message The message to send
 * @param string $from The sender ID
 * @return array Result of the SMS sending attempt
 */
function sendSMS($phoneNumber, $message, $from = 'SophysGlam') {
    // Africa's Talking API credentials
    // IMPORTANT: Replace these with your actual credentials
    $username = 'sandbox'; // Replace with your actual username
    $apiKey = 'YOUR_ACTUAL_API_KEY_HERE'; // Replace with your actual API key
    
    // For production, use: https://api.africastalking.com/version1/messaging
    // For sandbox, use: https://api.sandbox.africastalking.com/version1/messaging
    $url = 'https://api.sandbox.africastalking.com/version1/messaging';
    
    $data = [
        'username' => $username,
        'to' => $phoneNumber,
        'message' => $message,
        'from' => $from
    ];
    
    $headers = [uto-open WhatsApp 
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded',
        'apiKey: ' . $apiKey
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return [
            'success' => false,
            'error' => 'CURL Error: ' . $error,
            'phone' => $phoneNumber
        ];
    }
    
    $responseData = json_decode($response, true);
    
    if ($httpCode === 201 || $httpCode === 200) {
        return [
            'success' => true,
            'response' => $responseData,
            'phone' => $phoneNumber,
            'message_sent' => true
        ];
    } else {
        return [
            'success' => false,
            'error' => 'HTTP Error: ' . $httpCode,
            'response' => $responseData,
            'phone' => $phoneNumber
        ];
    }
}

/**
 * Alternative: Send SMS using a different provider (e.g., Twilio)
 * Uncomment and modify this function if you prefer to use Twilio
 */
/*
function sendSMSWithTwilio($phoneNumber, $message) {
    $accountSid = 'YOUR_TWILIO_ACCOUNT_SID';
    $authToken = 'YOUR_TWILIO_AUTH_TOKEN';
    $twilioNumber = 'YOUR_TWILIO_PHONE_NUMBER';
    
    $url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";
    
    $data = [
        'From' => $twilioNumber,
        'To' => $phoneNumber,
        'Body' => $message
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $accountSid . ':' . $authToken);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'success' => ($httpCode === 201),
        'response' => json_decode($response, true),
        'phone' => $phoneNumber
    ];
}
*/

// Log booking for record keeping
$logEntry = date('Y-m-d H:i:s') . " - New booking: {$clientName} ({$clientPhone}) - {$serviceName} on {$formattedDate} at {$time}\n";
file_put_contents('booking_log.txt', $logEntry, FILE_APPEND | LOCK_EX);

?>
