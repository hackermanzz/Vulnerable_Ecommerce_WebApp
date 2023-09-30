<?php

// Include config file
include "config.php";
session_start();

// Define variables and initialize with empty values
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate username

    $email = trim($_POST["email"]);
    $number = trim($_POST["number"]);
    $id = trim($_POST["id"]);

    if (empty($email)) {
        $_SESSION['profile_error'] = "Email is required";
        header("location: profile.php");
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['profile_error'] = "Invalsid email format";
        header("location: profile.php");
        exit();
    } elseif (!preg_match('/^[0-9_]+$/', trim($_POST["number"]))) {
        $_SESSION['profile_error'] = "Phone number can only contain 8 digit numbers.";
        header("location: profile.php");
        exit();
    } elseif (!strlen($number) == 8) {
        $_SESSION['profile_error'] = "Phone number can only contain 8 digit numberss.";
        header("location: profile.php");
        exit();
    } else {
        // Prepare a select statement
        $sql = "UPDATE users SET email=?, number=? WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sii", $email, $number, $id);

            // Set parameters
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Password updated successfully. Destroy the session, and redirect to login page
                $sql = "SELECT id, username, email, number FROM users WHERE id = ?";

                if ($stmt = mysqli_prepare($conn, $sql)) {

                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "i", $id);

                    // Set parameters                        
                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {

                        // Store result
                        mysqli_stmt_store_result($stmt);

                        // Check if username exists, if yes then verify password
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            // Bind result variables
                            mysqli_stmt_bind_result($stmt, $id, $username, $email, $number);
                            if (mysqli_stmt_fetch($stmt)) {
                                // Password is correct, so start a new session
                                // Store data in session variables
                                if ($_SESSION['id'] == $id) {
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["username"] = $username;
                                    $_SESSION["email"] = $email;
                                    $_SESSION["number"] = $number;
                                    header("location: profile.php");
                                    exit();
                                } else {
                                    header("location: profile.php?id=" . $id);
                                    exit();
                                }
                                exit();
                            }
                        }
                    }
                }
            } else {
                $_SESSION['profile_error'] = "Oops! Something went wrong. Please try again later.";
                header("location: profile.php");
                $conn->close();
                exit();
            }
            $conn->close();
            mysqli_stmt_close($stmt);
        }
    }
} else {
    header("location: profile.php");
}