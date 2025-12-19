<?php
// Only handle POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: contact.html");
  exit;
}

// Grab + sanitize inputs
$name = trim($_POST["name"] ?? "");
$visitor_email = trim($_POST["email"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");

// Basic validation
if ($visitor_email === "" || $subject === "" || $message === "") {
  // Missing required fields -> bounce back
  header("Location: contact.html");
  exit;
}

// Validate email format
if (!filter_var($visitor_email, FILTER_VALIDATE_EMAIL)) {
  header("Location: contact.html");
  exit;
}

// Prevent header injection (strip CRLF)
$visitor_email = str_replace(["\r", "\n"], "", $visitor_email);
$name = str_replace(["\r", "\n"], " ", $name);
$subject = str_replace(["\r", "\n"], " ", $subject);

$email_from = "info@calvinlimm.com";
$to = "calvinlanlim@gmail.com";

$email_subject = "New Form Submission: " . $subject;

$email_body =
  "User Name: {$name}\n" .
  "User Email: {$visitor_email}\n" .
  "Subject: {$subject}\n\n" .
  "Message:\n{$message}\n";

$headers = "From: {$email_from}\r\n";
$headers .= "Reply-To: {$visitor_email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send and redirect
@mail($to, $email_subject, $email_body, $headers);

header("Location: contact.html");
exit;
?>
