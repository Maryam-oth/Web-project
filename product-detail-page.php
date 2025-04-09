<?php
session_start();
// Check if a session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
include('../include/header.php');
include('../include/connection.php');

// Check if the product ID is set in the URL
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query to fetch the details of the product with the given ID
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result === false) {
        echo "Error executing query: " . $conn->error;
    } else {
        // Check if the product exists
        if ($result->num_rows > 0) {
            // Fetch the product details
            $product = $result->fetch_assoc();
?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Product Detail - <?php echo $product['name']; ?></title>
                <link rel="stylesheet" href="product-detail-styles.css">
                <?php include("../include/fonts.html"); ?>
            </head>

            <body>
                <!-- Product Details -->
                <div class="product-detail-container">
                    <div class="product-detail-info">
                        <div class="product-image">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['picture']); ?>" alt="<?php echo $product['name']; ?>">
                        </div>
                        <div class="product-details">
                            <h1><?php echo $product['name']; ?></h1>
                            <p>Description: <span style="font-weight: lighter"><?php echo $product['description']; ?></span></p>
                            <p>Price: <span style="font-weight: lighter"><?php echo $product['price']; ?> SAR</span></p>
                            <!-- Add to Cart button -->
                            <form action="cart-page.php" method="POST" onsubmit="return validateQuantity()" >
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <div class="quantity-input">
                                    <lable>Quantity: <input type="number" id="quantity" name="quantity" min="1" value="1" step="1" required></lablel>
                                </div>
                                <div class="add-to-cart-input">
                                    <button type="submit" class="add-to-cart">Add to Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    function validateQuantity() {
                        var quantityInput = document.getElementById('quantity');
                        var quantity = parseInt(quantityInput.value);
                        var maxStock = <?php echo $product['stock']; ?>;
                        if (isNaN(quantity) || quantity <= 0 || quantity > maxStock) {
                            alert('Please enter a valid quantity within the available stock.');
                            return false;
                        }
                        return true;
                    }
                </script>
            </body>

            </html>
<?php
        } else {
            echo "Product not found.";
        }
    }
} else {
    echo "Product ID not provided.";
}

// Close the database connection
$conn->close();

include('../include/footer.php');
?>
