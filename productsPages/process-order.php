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
include('../include/header.php'); 
include('../include/connection.php'); 
include("../include/fonts.html"); 


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $address = $_POST["address"];

    // Retrieve the cart data from the session
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

    $pastPurchases = array();

    foreach ($cart as $id => $item) {
        $quantity = $item['quantity'];
        
        $sql = "SELECT * FROM products WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        $pastPurchases[] = array(
            'product_name' => $product['name'],
            'id' => $id,
            'quantity' => $quantity,
            'price' => $product['price']
        );

        $newStock = $product['stock'] - $quantity;
        
        $updateSql = "UPDATE products SET stock = $newStock WHERE id = $id";
        mysqli_query($conn, $updateSql);
    }

    // Serialize the past purchases array
    $pastPurchasesSerialized = serialize($pastPurchases);

    // Set the cookie with the serialized data
    setcookie('past_purchases', $pastPurchasesSerialized, time() + (86400 * 30), '/'); // Cookie valid for 30 days

    // Display the thank you message with the provided name
    echo "<div class='cart-summary'>";
    echo "<h1>Thank You, $name!</h1>";

    // Display the order details
    echo "<div class='checkout-form'>";
    echo "<h2>Order Details</h2>";
    echo "<p>Name: $name</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Address: $address</p>";

    // Display the product details
    echo "<h2>Product Details</h2>";
    echo "<ul>";
    foreach ($pastPurchases as $purchase) {
        echo "<li>{$purchase['quantity']} x {$purchase['product_name']} - {$purchase['price']} SAR</li>";
    }
    echo "</ul>";

    echo "</div>";

    echo "</div>";

    // Clear the cart session data after the order is processed
    unset($_SESSION['cart']);
} else {
    header("Location: checkout-page.php");
    exit();
}

include('../include/footer.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Purchases</title>
    <link rel="stylesheet" href="process-order.css">
</head>
</html>
