<?php
//function prod_display($category) {
$category = 'apparels';

function prod_display($category) {
    include 'config.php';

    $sql = "SELECT * FROM products WHERE product_category = '" . $category . "'";
    if ($result = $conn->query($sql)) {
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="pro" id="prdout" onclick="product_button()">
                        <img src="<?php echo $row["product_img"] ?>" alt="<?php echo $row["product_name"] ?>">
                        <div class="des">
                            <span><?php echo $row["product_desc"] ?></span>
                            <h5><?php echo $row["product_name"] ?></h5>
                            <div class="star">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h4><?php echo $row["product_price"] ?></h4>
                            <form action="product.php" method="post">
                                <input type="hidden" name="pname" value="<?php echo $row["product_name"] ?>">
                                <input type="hidden" name="pdesc" value="<?php echo $row["product_desc"] ?>">
                                <input type="hidden" name="pprice" value="<?php echo $row["product_price"] ?>">
                                <input type="hidden" name="pimage" value="<?php echo $row["product_img"] ?>">
                                <input type="hidden" name="pid" value="<?php echo $row["product_id"] ?>">
                                <button type="submit">
                                    <i class="fal fa-shopping-cart cart"></i> 
                                </button>
                            </form> 
                        </div>
                    </div>
                    <?php
                }
                $result->close();
            } else {
                
            }
            $conn->close();
        } else {
            echo"nothing";
        }
    }
}