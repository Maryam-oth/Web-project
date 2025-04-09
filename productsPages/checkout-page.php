<?php
// Check if a session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
*/

// Include header
include('../include/header.php');
include('../include/connection.php');

// Function to fetch product details by ID
function getProductDetails($id)
{
    global $conn;
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return null;
    }
}

// Calculate total amount
function calculateTotal()
{
    $total = 0;
    foreach ($_SESSION['cart'] as $id => $item) {
        // Fetch the product details from the database
        $product = getProductDetails($id);
        if ($product) {
            // Calculate subtotal using the fetched price and quantity from the session
            $subtotal = $product['price'] * $item['quantity'];
            $total += $subtotal;
        }
    }
    return $total;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="checkout-page.css">
    <script src="validateForm.js"></script>
    <?php include("../include/fonts.html"); ?>
</head>

<body>
<div class="container-between-header-footer">
    <!-- Cart Summary -->
    <div class="cart-summary">
        <h2>Cart Summary</h2>
        <!-- Display cart items and total here -->
        <?php
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $item) {
                $product = getProductDetails($id);
                if ($product) {
                    echo "<div class='cart-item'> \n";
                    echo "<span>" . $product['name'] . "</span> \n";
                    echo "<span>Quantity: " . $item['quantity'] . "</span> \n ";
                    echo "<span>Price: " . $product['price'] * $item['quantity'] . "SAR </span> \n";
                    echo "</div>";
                }
            }
            echo "<div class='total'>Total Amount:" . calculateTotal() . "SAR </div>";
        } else {
            echo "<p class='no-items'> No items in the cart.</p>";
        }
        ?>
    </div>

    <!-- Checkout Form -->
    <div class="checkout-form">
        <h2>Enter Your Information</h2>
        <form id="checkoutForm" action="process-order.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>
            <!-- Payment Information -->
            <h2>Payment Information</h2>
            <div class="form-group">
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" required>
            </div>
            <div class="form-group">
                <label for="expirationDate">Expiration Date:</label>
                <input type="text" id="expirationDate" name="expirationDate" placeholder="MM/YYYY" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
            </div>
            <div class="form-group">
                <label for="billingAddress">Billing Address:</label>
                <textarea id="billingAddress" name="billingAddress" required></textarea>
            </div>
            <button type="submit" class="buy-button">Buy</button>
        </form>
    </div>
</div>

    <?php include('../include/footer.php'); ?>

</body>
</html>