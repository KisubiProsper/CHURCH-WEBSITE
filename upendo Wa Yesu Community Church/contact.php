<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        header("Location: index.html?status=error");
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?status=error");
        exit;
    }
    
    // Email configuration
    $to = "info@kisubizabron@gmail.com"; // Replace with your actual email
    $subject = "New Message from Church Website - From: $name";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Email body
    $body = "You have received a new message from your church website:\n\n";
    $body .= "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: " . ($phone ? $phone : "Not provided") . "\n\n";
    $body .= "Message:\n$message\n\n";
    $body .= "Sent on: " . date('Y-m-d H:i:s');
    
    // Send email
    if (mail($to, $subject, $body, $headers)) {
        // Log the message to a file (optional)
        $log_message = "[" . date('Y-m-d H:i:s') . "] Message from $name ($email): " . substr($message, 0, 100) . "...\n";
        file_put_contents('contact_log.txt', $log_message, FILE_APPEND | LOCK_EX);
        
        header("Location: index.html?status=success");
        exit;
    } else {
        header("Location: index.html?status=error");
        exit;
    }
} else {
    // If not POST request, redirect to home
    header("Location: index.html");
    exit;
}
?>