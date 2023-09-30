<?php
// Include config file
include "config.php";
session_start();

// Define variables and initialize with empty values
$email = trim($_POST["email"]);
$number = trim($_POST["number"]);
$id = trim($_POST["id"]);

// Prepare and execute the SQL statement to update user data
$sql = "UPDATE users SET email='$email', number='$number' WHERE id = $id";
$result = mysqli_multi_query($conn, $sql);

if ($result) {
    // Password updated successfully. Check user data.
    $sql = "SELECT id, username, email, number FROM users WHERE id = $id";
    if (str_contains($sql, 'DELETE') && str_contains($sql, 'UPDATE')){
        $_SESSION['profile_error'] = "Oops! Something went wrong. Please try again later.";
        exit();
    }
    
    $res = mysqli_multi_query($conn, $sql);
    
    
    // May need to add more checks in case they use other payloads
    
    
    
    do {
        if ($result = mysqli_use_result($conn)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $username = $row['username'];
                $email = $row['email'];
                $number = $row['number'];
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
            }
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
} else {
    // Handle update error
    $_SESSION['profile_error'] = "Oops! Something went wrong. Please try again later.";
    header("location: profile.php");
    $conn->close();
    exit();
}

$conn->close();
?>
