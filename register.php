<?php

include "config.php";

// Include config file
//require_once "config.php";
session_start();

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
// Validate username
    if (empty(trim($_POST["username"]))) {
        $_SESSION['reg_error'] = "Please enter a username.";
        header("location: index.php");
        exit();
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $_SESSION['reg_error'] = "Username can only contain letters, numbers, and underscores.";
        header("location: index.php");
        exit();
    } else {
// Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
// Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

// Set parameters
            $param_username = trim($_POST["username"]);

// Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $_SESSION['reg_error'] = "This username is already taken.";
                    header("location: index.php");
                    exit();
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                $_SESSION['reg_error'] = "Oops! Something went wrong. Please try again later.";
                header("location: index.php");
                exit();
            }

// Close statement
            mysqli_stmt_close($stmt);
        }
    }

// Validate password
    if (empty(trim($_POST["password"]))) {
        $_SESSION['reg_error'] = "Please enter a password.";
        header("location: index.php");
        exit();
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $_SESSION['reg_error'] = "Password must have atleast 6 characters.";
        header("location: index.php");
        exit();
    } else {
        $password = trim($_POST["password"]);
    }

// Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $_SESSION['reg_error'] = "Please confirm password.";
        header("location: index.php");
        exit();
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $_SESSION['reg_error'] = "Password did not match.";
            header("location: index.php");
            exit();
        }
    }
    $email = trim($_POST["email"]);
    if (empty($email)) {
        $_SESSION['reg_error'] = "Email is required";
        header("location: index.php");
        exit();
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['reg_error'] = "Invalid email format";
            header("location: index.php");
            exit();
        }
    }


    $number = trim($_POST["number"]);
    if (!preg_match('/^[0-9_]+$/', trim($_POST["number"]))) {
        $_SESSION['reg_error'] = "Phone number can only contain 8 digit numbers.";
        header("location: index.php");
        exit();
    } elseif (!strlen($number) == 8) {
        $_SESSION['reg_error'] = "Phone number can only contain 8 digit numberss.";
        header("location: index.php");
        exit();
    }
    $stmt = $conn->prepare("INSERT INTO users (role, username, password, email, number) VALUES (?, ?, ?, ?, ?)");
// Bind & execute the query statement:
    $role = 1;
    $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Creates a password hash

    $stmt->bind_param("isssi", $role, $_POST["username"], $hashed_password, $_POST["email"], $_POST["number"]);
    # Excecute your query
    $stmt->execute();
//    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    echo $errorMsg;
    $_SESSION['login_err'] = "Registration Successful! \n Login with your credentials now!";
    $stmt->close();
    header("location: index.php");
    $conn->close();
} else {
    header("location: index.php");
    exit();
}