<?php
session_start();
include "access_control.php";
$permitted = array(0, 1);
access_control($permitted);

if (isset($_GET['id']) && $_SESSION['role'] === 0) {

    include "config.php";
    $sql = "SELECT id,role,username,email,number FROM users WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $sqlid);
        // Set parameters
        $sqlid = $_GET['id'];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $role, $username, $email, $number);
                if (mysqli_stmt_fetch($stmt)) {

                    $prof_username = $username;
                    $prof_email = $email;
                    $prof_number = $number;
                    $prof_id = $id;
                } else {
                    // Password is not valid, display a generic error message
                    $_SESSION['profile_err'] = "User does not exist.";
                }
            }
        } else {
            // Username doesn't exist, display a generic error message
            $_SESSION['profile_err'] = "User does not exist.";
        }
    } else {
        $_SESSION['profile_err'] = "Oops! Something went wrong. Please try again later.";
    }
} else {
    $prof_username = $_SESSION['username'];
    $prof_email = $_SESSION['email'];
    $prof_number = $_SESSION['number'];
    $prof_id = $_SESSION['id'];
}
mysqli_stmt_close($stmt);
mysqli_close($conn);

include "inc/nav.inc.php";
?>


<body>
    <div class="container">
        <table  style="margin:auto;margin-top:10px;">
            <?php
            if (!empty($_SESSION['profile_err'])) {
                echo '<div class="alert alert-primary" style="color:red">' . $_SESSION['profile_err'] . '</div>';
            }
            ?>
            <th></th>
            <th></th>

            <tr>
                <th colspan="2">User Profile</th>
            </tr>
            <form method='post' <?php echo ($_SESSION['username'] == '00__SNIPER_MONKEY__00') ? 'action="update_profile.php"' : 'action="update_profile_safe.php"'; ?>>
                <tr>
                    <td>Email</td>
                    <td><input type="email" class="form-control" value="<?php echo $prof_email; ?>" name="email"></td>
                </tr>
                <input type="hidden" class="hidden" value="<?php echo $prof_id; ?>" name="id">
                <tr>
                    <td>Number</td>
                    <td><input type="text" required pattern="[0-9]{8}" class="form-control" oninvalid="this.setCustomValidity('Please enter 8 digits only')" oninput="this.setCustomValidity('')"value="<?php echo $prof_number ?>" name="number"></td>
                </tr>
                <tr colspan="2">
                    <td colspan="2"><button type="submit" class="btn btn-success">Save Changes</button></td>
                </tr>
                <tr><br></tr>
                <tr>
                    <td colspan="2">
                        <button type="button" class="btn btn-danger" onclick="window.location.href = 'delete_user.php?id=<?php echo $_SESSION['id'] ?>'">Delete Account</button>
                    </td>
                </tr>
            </form>
        </table>
    </div>

    <?php
    include "config.php";
    $sql = "SELECT * FROM purchase_history WHERE user_id = " . $prof_id;
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result)) {
        ?>
        <div class='container'>
            <button type='button' class='collapsible'>Purchase History</button>
            <div class='content'>
                <table class='table table-hover' id='customers'>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Size</th>
                        <th>Date Bought</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>";
                        echo $row['product_name'];
                        echo "</td>";
                        echo "<td>";
                        echo $row['quantity'];
                        echo "</td>";
                        echo "<td>";
                        echo $row['size'];
                        echo "</td>";
                        echo "<td>";
                        echo date("d/m/Y", strtotime($row['time']));
                        echo "</td>";
                        echo "<td>";
                        echo $row['subtotal'];
                        echo "</td>";
                    }
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<div class='container'>";
                    echo "<h4>You haven't bought any items yet!</h4>";
                    echo "</div>";
                }
                mysqli_free_result($result);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                ?>
            </table></div></div>
</body>
</html>

<script>
    //As this js is only applicable to this page, it was decided to keep it in here 
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
</script>
<section id="newsletter" class="section-m1 section-p1">
    <div class="newstext">
        <h4>Sign Up For Newsletters </h4>
        <p>Get E-mail updates about our latest shop and <span>special offers.</span></p>
    </div>
    <div class="form">
        <input type="text" name="" placeholder="Your email address" id="">
        <button class="normal">Sign Up</button>
    </div>
</section>

<?php
unset($_SESSION['profile_error']);
include "inc/footer.inc.php";

