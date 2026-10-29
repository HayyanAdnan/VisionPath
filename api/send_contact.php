<?php
// api/send_contact.php - Contact Form Handler
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: same-origin');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode([
            'success' => false, 
            'message' => 'All required fields must be filled'
        ]);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Please enter a valid email address'
        ]);
        exit;
    }
    
    // Save to database (optional - create contact_messages table)
    // For now, log to file
    $logEntry = "=== Contact Form Submission ===\n";
    $logEntry .= "Date: " . date('Y-m-d H:i:s') . "\n";
    $logEntry .= "Name: $name\n";
    $logEntry .= "Email: $email\n";
    $logEntry .= "Phone: $phone\n";
    $logEntry .= "Subject: $subject\n";
    $logEntry .= "Message: $message\n";
    $logEntry .= "----------------------------------------\n\n";
    
    // Save to file (in a real app, you'd save to database and send email)
    $logFile = __DIR__ . '/../contact_log.txt';
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    
    // Send email notification (configure with your SMTP settings)
    // For now, just return success
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you! We will get back to you within 24 hours.'
    ]);
    
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid request method'
    ]);
}
?>