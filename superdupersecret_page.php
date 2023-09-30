<?php
session_start();

if ($_SESSION['role'] != 0 || !isset($_SESSION['role'])) {
    header("location: index.php");
} else {
    print("{
        Hey Ninja, please remember to change the password for sniper. \n i dont really remember the full name liao.
        username: I forgot
        password: !!!sean!!!
    }");
}

if (!isset($_SESSION['loggedin'])) {
    unset($_SESSION['role']);
}
