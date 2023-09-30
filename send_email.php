<?php
session_start();
$_SESSION['role'] = 0;
function validateEmail($email)
{
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['feed_back_rep'] = "Invalid email format";
        header('Location: contact.php');
        exit();
    }


    // Send confirmation email (if needed)
    $subject = 'Confirmation Email from StyleSphere';
    $message = 'Your feedback has been received! Thank you for choosing us!';
    mail($email, $subject, $message);

    $_SESSION['feed_back_rep'] = 'Your feedback is greatly appreciated!';
    header('Location: contact.php');
}


$_SESSION['email'] = $_POST['email'];
validateEmail($_SESSION['email']);
