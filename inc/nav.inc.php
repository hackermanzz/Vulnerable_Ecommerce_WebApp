<?php
//session_start()
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StyleSphere Singapore</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <script src="js/console.js"></script>

</head>

<body>
    <section id="header">
        <a href="index.php"><img src="images/StyleSphere-Logo.png" class="logo" alt="" height="120px"></a>

        <div>
            <ul id="navbar">
                <li><a class="index" href="index.php">Home</a></li>
                <li>
                    <div class="dropdown">
                        <button class="dropbtn">Shop </button>
                        <div class="dropdown-content">
                            <a href="apparels.php">Apparels</a>
                            <a href="bottoms.php">Bottoms</a>
                            <a href="accessories.php">Accessories</a>
                            <a href="bags.php">Bags</a>
                        </div>

                </li>
                <li><a href="sale.php">Sale</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] = true && $_SESSION['role'] == 0) : ?>
                    <li><a href="console.php">Console</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] = true) : ?>
                    <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>
                    <li><a href="wishlist.php">Wishlist</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else : ?>
                    <li id="lg-bag"><a href="cart.php"><i class="far fa-shopping-bag"></i></a></li>

                    <li id="login-btn" onclick="document.getElementById('loginpop').style.display = 'block'" style="width:auto;"><i class="far fa-user"></i></a></li>
        </div>
        </div>

        <div id="loginpop" class="modal">
            <form class="modal-content animate" action="login.php" method="post">
                <div class="imgcontainer">
                    <span onclick="document.getElementById('loginpop').style.display = 'none'" class="close" title="Close Modal">&times;</span>
                </div>

                <div class="container">
                    <h3>Sign In</h3><br>
                    <?php
                    if (!empty($_SESSION['login_err'])) {
                        echo '<div class="alert alert-primary" style="color:red">' . $_SESSION['login_err'] . '</div>';
                    }
                    ?>

                    <label for="uname"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="username" required>

                    <label for="psw"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required>

                    <button class="login" type="submit">Login</button><br><br>
                    <label>
                        <input type="checkbox" checked="checked" name="remember"> Remember me
                    </label>
                </div>

                <div class="container" style="background-color:#f1f1f1">
                    <button type="button" onclick="document.getElementById('loginpop').style.display = 'none'" class="cancelbtn">Cancel</button>
                    <span class="psw">Forgot <a href="#">password?</a></span>
                    <a style="margin-right: 15px;" id="register-btn" onclick="document.getElementById('regpop').style.display = 'block';document.getElementById('loginpop').style.display = 'none'">Register</a>

                </div>
            </form>
        </div>
        </div>

        <div id="regpop" class="modal">
            <form class="modal-content animate" action="register.php" method="post">
                <div class="imgcontainer">
                    <span onclick="document.getElementById('regpop').style.display = 'none'" class="close" title="Close Modal">&times;</span>
                </div>

                <div class="container">
                    <h3>Register</h3><br>
                    <?php
                    if (!empty($_SESSION['reg_error'])) {
                        echo '<div class="alert alert-primary" style="color:red">' . $_SESSION['reg_error'] . '</div>';
                    }
                    ?>



                    <label for="uname"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="username" required>

                    <label for="psw"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required>

                    <label for="psw"><b>Confirm Password</b></label>
                    <input type="password" placeholder="Re-enter Password" name="confirm_password" required>

                    <label for="psw"><b>Email</b></label>
                    <input type="email" placeholder="Enter Email" name="email" required>

                    <label for="number"><b>Contact Number</b></label>
                    <input type="tel" placeholder="Enter Contact Number" name="number" required>

                    <button class="register" type="submit">Register</button><br><br>
                </div>

                <div class="container" style="background-color:#f1f1f1">
                    <button type="button" onclick="document.getElementById('regpop').style.display = 'none'" class="cancelbtn">Cancel</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
    <div id="mobile">
        <a class="shop-icon" href="cart.php"><i class="far fa-shopping-bag"></i></a>
        <div class="dropdowncollapse">
            <i class="fas fa-outdent" onclick="toggleDropdown();toggleNav()"></i>
            <div class="dropdown-content" id="dropdown-content">
                <a href="sale.php">Sale</a>
                <a href="conatct.php">Contact</a>
                <a href="apparels.php">Apparels</a>
                <a href="bottoms.php">Bottoms</a>
                <a href="accessories.php">Accessories</a>
                <a href="bags.php">Bags</a>
            </div>
        </div>
    </div>

    <script>
        var dropdownContent = document.getElementById("dropdown-content");

        document.addEventListener("click", function(event) {
            if (!event.target.closest(".dropdowncollapse") && dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            }
        });

        function toggleDropdown() {
            dropdownContent.style.display === "block" ?
                dropdownContent.style.display = "none" :
                dropdownContent.style.display = "block";
        }
    </script>




    </section>