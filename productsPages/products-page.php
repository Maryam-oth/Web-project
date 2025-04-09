
<?php include('../include/header.php'); ?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thameen Jewelry - Product Detail</title>
    <link rel="stylesheet" href="products-style.css"> 
</head>
    
<?php include("../include/fonts.html"); ?>

    
<body>

    <section class="products">
        
        <?php
            include "../include/connection.php"; 
            
            $sql = "SELECT id, name, price, picture FROM products";
            
            $result = $conn->query($sql);

            // Check if the query was successful
            if ($result === false) {
                echo "Error executing query: " . $conn->error;
            } else {
                // Check if there are any rows returned
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="product" onclick="window.location.href=\'../productsPages/product-detail-page.php?id=' . $row["id"] . '\'">';
                        echo '<div class="product-info">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row["picture"]) . '" alt="' . $row["name"] . '">';
                        echo '<h2>' . $row["name"] . '</h2>';
                        echo '<span class="price">' . $row["price"] . 'SAR</span>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "0 results";
                }
            }

            // Close the database connection
            $conn->close();
        ?>
    </section>
</body>
</html>

    <?php include('../include/footer.php'); ?> 