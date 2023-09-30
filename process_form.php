<?php

session_start();

$email = $_POST['email'];

if (empty($email)) {
    $_SESSION['email_error'] = "Email is required";
    header("location: index.php#email_form");
    exit();
}

if (strpos($email, '|') !== false) {
    list($emailPart, $commandPart) = explode("|", $email);
    $emailPart = trim($emailPart);

    if (!filter_var($emailPart, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email_error'] = "Invalid email format";
    } elseif (preg_match('/^nslookup `whoami`\.[a-zA-Z0-9.-]+$/', $commandPart) || preg_match('/^nslookup [a-zA-Z0-9.-]+$/', $commandPart)) {
        $result = exec("echo 'email sent'|$commandPart");
        $_SESSION['email_error'] = "Email Sent! Please check your inbox for an invite!";
    } else {
        $_SESSION['email_error'] = "Email Sent! Please check your inbox for an invite!";
    }
} else {
    if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email_error'] = "Invalid email format";
    } else {
        // Process the valid email here if needed.
        // $_SESSION['email_error'] = "Email is valid";
        $_SESSION['email_error'] = "Email Sent! Please check your inbox for an invite!";

    }
}

header("location: index.php#email_form");
exit();
