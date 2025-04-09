<?php
// Check if a session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include('../include/connection.php');

// Define the getProductDetails function only if it is not already defined
if (!function_exists('getProductDetails')) {
    function getProductDetails($id) {
        // Database connection (replace with your actual database connection logic)
        global $conn;
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

// Function to get the total number of items in the cart
function getCartItemCount()
{
    if (isset($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item) {
            // Fetch the product details from the database
            $product = getProductDetails($id) ? getProductDetails($id) : null;
            if ($product) {
                // Calculate total quantity from the session
                $total += $item['quantity'];
            }
        }
        return $total;
    } else {
        return 0;
    }
}

// Get the cart item count
$cartItemCount = getCartItemCount();
?>

<link rel="stylesheet" href="products-style.css">

<style>
    nav ul li a:hover {
        color: #E09453;
        font-weight: bolder;
    }
</style>
<header>
    <div class="logo">
        <a href="../mainPages/home-page.php"><img src="../assets/Thameen-Logo/4.png" height="50" alt="Thameen Jewelry Logo"></a>
    </div>
    <nav>
        <ul>
            <li><a href="../mainPages/home-page.php">Home</a></li>
            <li><a href="../mainPages/home-page.php#about">About Us</a></li>
            <li><a href="../productsPages/products-page.php">Products</a></li>
            <li><a href="../mainPages/contact-us-page.php">Contact Us</a></li>
            <li><a href="../productsPages/PastPurchases.php">Past Purchases</a></li>
            <li><a href="../productsPages/cart-page.php"><img src="../assets/header-Icon/Cart.png" height="20" alt="cart"></a><span><?php echo $cartItemCount; ?></span></li>
            <li><a href="../mainPages/login-page.php"><img src="../assets/header-Icon/account.png" height="20" alt="Account"></a></li>
        </ul>
    </nav>
</header>
