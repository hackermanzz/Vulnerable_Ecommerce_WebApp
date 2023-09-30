<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <?php
        // Initialize the session
        session_start();
        include "config.php";

        function trackLoginAttempt() {
            // Check if session variable exists, if not, initialize it
            if (!isset($_SESSION["login_attempts"])) {
                $_SESSION["login_attempts"] = 1;
                $_SESSION["last_login"] = time();
                $_SESSION['login_err'] = "Invalid username or password.";
            } else {
                // If it exists, increment the attempts and update the last login time
                $_SESSION["login_attempts"]++;
                $_SESSION["last_login"] = time();
                $_SESSION['login_err'] = "Invalid username or password.";
            }
        }

        function lockout(){
            $lockout_time = 30;
            if(isset($_SESSION['login_attempts'])){
                if($_SESSION['login_attempts']=='3'){
                    if(time() - $_SESSION['last_login'] < $lockout_time){
                        $_SESSION['login_err'] = "You have been locked out for unsuccessfull authentication. Try again in ". (30 - (time() - $_SESSION['last_login'])). " Seconds";
                        header("location: index.php");
                        exit();
                    } else {
                        unset($_SESSION['login_attempts']);
                    }
                    
                }
            }
        }

        // Check if the user is already logged in, if yes then redirect him to welcome page
         if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
             header("location: index.php");
             exit;
         }
        // Include config file
        // Define variables and initialize with empty values
        $username = $password = "";
        $username_err = $password_err = $login_err = "";


        // Processing form data when form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if username is empty
            if (empty(trim($_POST["username"]))) {
                $_SESSION['login_err'] = "Invalid username or password.";
                header("location: index.php");
                exit();
            } else {
                $username = trim($_POST["username"]);
            }


            // Check if password is empty
            if (empty(trim($_POST["password"]))) {
                $_SESSION['login_err'] = "Invalid username or password.";
                header("location: index.php");
                exit();
            } else {
                $password = trim($_POST["password"]);
            }
            lockout();
            
            // Validate credentials
            //if (empty($_SESSION['login_err'])) {
                // Prepare a select statement
                $sql = "SELECT id,role,"
                        . " username, password, email, number FROM users WHERE username = ?";
                
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_username);
                    // Set parameters
                    $param_username = $username;
                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Store result
                        mysqli_stmt_store_result($stmt);
                        // Check if username exists, if yes then verify password
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            // Bind result variables
                            mysqli_stmt_bind_result($stmt, $id, $role, $username, $hashed_password, $email, $number);
                            if (mysqli_stmt_fetch($stmt)) {
                                if (password_verify($password, $hashed_password) ) {
                                    // Password is correct, so start a new session
                                    echo "<div style='display: none;'>https://bitly.com/98K8eH</div>";
                                    if (empty($_SESSION['login_err'])) {

                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["id"] = $id;
                                        $_SESSION["role"] = $role;
                                        $_SESSION["username"] = $username;
                                        $_SESSION["email"] = $email;
                                        $_SESSION["number"] = $number;

                                        // Redirect user to welcome page
                                        $_SESSION['login_err'] = "Worked";
                                        if(isset($_SESSION['login_attempts'])){
                                            $_SESSION['login_attempts'] = 0;
                                        }
                                        header("location: index.php");
                                        exit();
                                    } 
                                    else {
                                        header("location: index.php");
                                        exit();
                                    }
                                } else {
                                    trackLoginAttempt();
                                    header("location: index.php");
                                    exit();
                                }
                            }
                        } else {
                            trackLoginAttempt();
                            header("location: index.php");
                            exit();
                        }
                    } else {
                        $_SESSION['login_err'] = "Oops! Something went wrong. Please try again later.";
                        header("location: index.php");
                        exit();
                    }
                    $_SESSION['login_err'] = "Oops! Something went wrong. Please try again later.";
                    header("location: index.php");
                    mysqli_stmt_close($stmt);
                }
//            } else {
//                header("location: index.php");
//                exit();
//            }
            
            // Close connection
            mysqli_close($conn);
        }
        header("location: index.php");
        ?>
        
    </body>
</html>
