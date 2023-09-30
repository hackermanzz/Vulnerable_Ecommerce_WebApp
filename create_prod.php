<?php

include "access_control.php";
$permitted = array(0);
access_control($permitted);

$prodname = $_POST['prodname'];
$prodprice = $_POST['prodprice'];
$proddesc = $_POST['proddesc'];
$prodcategory = $_POST['prodcategory'];
$success = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_FILES['prodimage'])) {
        $_SESSION['prod_err'] = "Image is required";
        header("location: console.php");
        exit();
    } elseif (empty($prodname)) {
        $_SESSION['prod_err'] = "Name is required";
        header("location: console.php");
        exit();
    } elseif (empty($prodprice)) {
        $_SESSION['prod_err'] = "Price is required";
        header("location: console.php");
        exit();
    } elseif (!is_numeric($prodprice)) {
        $_SESSION['prod_err'] = "Price should be numbers";
        header("location: console.php");
        exit();
    } elseif (empty($proddesc)) {
        $_SESSION['prod_err'] = "Description is required";
        header("location: console.php");
        exit();
    } elseif (empty($prodcategory)) {
        $_SESSION['prod_err'] = "Category is required";
        header("location: console.php");
        exit();
    } else {
        $image_name = $_FILES['prodimage']['name'];
        $image_tmp = $_FILES['prodimage']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Check if the uploaded file is an image 
        if (!in_array($image_ext, array("jpg", "jpeg", "png"))) {
            $success = false;
            $_SESSION['prod_err'] = "File type not allowed. Please upload a JPG, JPEG, PNG, file.";
            header("location: console.php");
            exit();
        } else {
            // Upload the file to a directory 

            $new_img_name = uniqid("img-") . '.' . $image_ext;
            $upload_dir = "./images/" . $prodcategory . "/";
            $file_newpath = $upload_dir . basename($new_img_name);
//
            if (move_uploaded_file($image_tmp, $file_newpath)) {
                include "config.php";
                $stmt = $conn->prepare("INSERT INTO products (product_name, product_price, product_desc, product_img, product_category) VALUES (?, ?, ?, ?, ?)");
                // Bind & execute the query statement:
                $stmt->bind_param("sisss", $prodname, $prodprice, $proddesc, $file_newpath, $prodcategory);
                # Excecute your query
                $stmt->execute();
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $_SESSION['prod_err'] = "Creation Successful!";
                header("location: console.php");
                exit();
            }
        }
    }
} else {
    header("location: console.php");
    exit();
}