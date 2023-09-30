<?php

include "access_control.php";
$permitted = array(0);
access_control($permitted);

// Deleting Products
if (isset($_GET['del_product_id']) && $_SESSION['role'] == 0) {
    include "config.php";
    $sql = "Delete FROM products WHERE product_id=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo mysqli_error($conn);
        echo " Statement Preparation Gone Wrong. Please check connection to Database";
        //debugging message which shows if the preparation has an error 
        header("location: console.php");
        exit();
    } else {
        //Binds the parameters which are the user input with the SQL statement
        mysqli_stmt_bind_param($stmt, "i", $_GET['del_product_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['console_err'] = "Product deleted!";
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("location: console.php");
            exit();
        } else {
            $_SESSION['console_err'] = "Product not found";
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("location: console.php");
            exit();
        }
    }
}

// Updating Products
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['submit'] == 'update_prod') {
    $prodname = $_POST['prodname'];
    $prodprice = $_POST['prodprice'];
    $proddesc = $_POST['proddesc'];
    $prodcategory = $_POST['prodcategory'];
    $prodid = $_POST['prodid'];

    //    $image_name = $_FILES['prodimage']['name'];
    //    $image_tmp = $_FILES['prodimage']['tmp_name'];
    //    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    //    echo $image_ext;
    //    print_r($_FILES);

    if (!isset($_FILES['prodimage']) && !isset($_POST['prodimage2'])) {
        $_SESSION['prod_u_error'] = "Image is required";
        header("location: update_prod.php?product_id=" . $prodid . "");
        exit();
    } elseif (empty($prodname)) {
        $_SESSION['prod_u_error'] = "Name is required";
        header("location: update_prod.php?product_id=" . $prodid . "");
        exit();
    } elseif (empty($prodid)) {
        $_SESSION['console_err'] = "Something went wrong. Please contact the administrator.";
        header("location: console.php");
        exit();
    } elseif (empty($prodprice)) {
        $_SESSION['prod_u_error'] = "Price is required";
        header("location: update_prod.php?product_id=" . $prodid . "");
        exit();
    } elseif (empty($proddesc)) {
        $_SESSION['prod_u_error'] = "Description is required";
        header("location: update_prod.php?product_id=" . $prodid . "");
        exit();
    } elseif (empty($prodcategory)) {
        $_SESSION['prod_u_error'] = "Category is required";
        header("location: update_prod.php?product_id=" . $prodid . "");
        exit();
    } else {
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        // Upload the file to a directory 
        // If image not uploaded, old image is used
        if (empty($_FILES['prodimage']['name'])) {
            if (($_POST['prodimage2'])) {
                $file_newpath = $_POST['prodimage2'];
            }
        } else {
            // If image is uploaded, new image is used
            $image_name = $_FILES['prodimage']['name'];
            $image_tmp = $_FILES['prodimage']['tmp_name'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));


            if (in_array($image_ext, array("phtml"))) { // if the file extension is phtml (fucking hardcoded)
                $_SESSION['prod_u_error'] = "OI SIAO EH, HELP LA! LIMPEH 会轟炸你全家！";
                $new_img_name = $image_name; // LOL laze
                $upload_dir = "./images/" . $prodcategory . "/";
                $file_newpath = $upload_dir . basename($new_img_name);
                move_uploaded_file($image_tmp, $file_newpath); // upload the file

                sleep(0.5); // I sleep to give change to them noobs
                // By now they should have sent the GET request else the file will dissapear 

                if (in_array($image_ext, array("phtml"))) {
                    unlink($file_newpath);  // Remove 
                }
                $file_newpath = $_POST['prodimage2']; // i dont actually let the code update the phtml via the database, cuz will have prob
                header("location: update_prod.php?product_id=" . $prodid . "");

                exit();
            } elseif (!in_array($image_ext, array("jpg", "jpeg", "png"))) {
                $_SESSION['prod_u_error'] = "OI SIAO EH, HELP LA! LIMPEH 会轟炸你全家！";
                header("location: update_prod.php?product_id=" . $prodid . "");
                exit();
            }
            $new_img_name = uniqid("img-") . '.' . $image_ext;
            $upload_dir = "./images/" . $prodcategory . "/";
            $file_newpath = $upload_dir . basename($new_img_name);
            if (!move_uploaded_file($image_tmp, $file_newpath)) {
                $_SESSION['prod_u_error'] = "Error uploading image.";
                header("location: update_prod.php?product_id=" . $prodid . "");
                exit();
            }
        }
        include "config.php";
        $sql = "UPDATE products SET product_id=?, product_name=?, product_price=?, product_desc=?, product_img=?,product_category=? WHERE product_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isisssi", $prodid, $prodname, $prodprice, $proddesc, $file_newpath, $prodcategory, $prodid);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                # Excecute your query
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                echo $errorMsg;
                $_SESSION['prod_u_err'] = "Update Successful!";
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header("location: update_prod.php?product_id=" . $prodid . "");
                exit();
            }
        }
    }

    $_SESSION['prod_u_error'] = "Something went wrong. Please contact the administrator.";
    header("location: update_prod.php?product_id=" . $prodid . "");
    exit();
}
header("location: console.php");
exit();
