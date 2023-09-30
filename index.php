<?php
session_start();
include "inc/nav.inc.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <section>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="images/banner/1.png" alt="Los Angeles" style="width:100%;">
                </div>
                <div class="item">
                    <img src="images/banner/2.png" alt="Los Angeles" style="width:100%;">
                </div>
                <div class="item">
                    <img src="images/banner/3.png" alt="Los Angeles" style="width:100%;">
                </div>
                <div class="item">
                    <img src="images/banner/4.png" alt="Los Angeles" style="width:100%;">
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </section>

    <section id="feature" class="section-p1">
        <div class="fe-box">
            <a href="accessories.php">
                <img src="images/accessories/a9.jpg" alt="">
            </a>
            <h6>Accessories</h6>
        </div>
        <div class="fe-box">
            <a href="apparels.php">
                <img src="images/tops/t1.jpg" alt="">
            </a>
            <h6>Tops</h6>
        </div>
        <div class="fe-box">
            <a href="bags.php">
                <img src="images/bags/bag1.jpg" alt="">
            </a>
            <h6>Bags</h6>
        </div>
        <div class="fe-box">
            <a href="bottoms.php">
                <img src="images/bottoms/bo1.jpg" alt="">
            </a>
            <h6>Bottoms</h6>
        </div>
    </section>

    <section id="product1" class="section-p1">
        <h2>BEST SELLERS</h2>
        <p>StyleSphere Collection</p>
        <div class="pro-container">
            <?php
            include 'display_prod.php';
            prod_display('dress');
            ?>
        </div>
    </section>

    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4>Sign Up For Newsletters</h4>
            <p>Get E-mail updates about our latest shop and <span>special offers.</span></p>

        </div>
        <div class="form" id='email_form'>
            <form method="post" action="process_form.php">
                <?php
                if (isset($_SESSION['email_error'])) {
                    echo $_SESSION['email_error'];
                } ?>
                <input type="text" name="email" placeholder="Your email address">
                <button type="submit" class="normal">Sign Up</button>
            </form>
        </div>
    </section>

    <?php include 'inc/footer.inc.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>

<?php
if (!empty($_SESSION['login_err'])) {
    echo "<script type='text/javascript'>";
    echo "document.getElementById('login-btn').click();";
    echo "</script>";
} elseif (!empty($_SESSION['reg_error'])) {
    echo "<script type='text/javascript'>";
    echo "document.getElementById('register-btn').click();";
    echo "</script>";
}

unset($_SESSION['login_err']);
unset($_SESSION['username_err']);
unset($_SESSION['password_err']);
unset($_SESSION['reg_error']);
?>