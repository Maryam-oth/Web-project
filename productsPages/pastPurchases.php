<?php 
include('../include/connection.php');
include('../include/header.php'); 

// Start the session
$haveOrdered = false;

// Check if the past purchases cookie is set
if(isset($_COOKIE['past_purchases'])) {
    // Retrieve past purchases from cookie
    $pastPurchasesSerialized = $_COOKIE['past_purchases'];
    $pastPurchases = unserialize($pastPurchasesSerialized);
        $haveOrdered = true;

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THAMEEN- Past Purchases</title>
    <link rel="stylesheet" href="products-style.css">
    <?php include("../include/fonts.html"); ?>
</head>
<body>
    <div class="containerp">
<?php if ($haveOrdered): ?>

        <h1 style="color: #0A4049">Past Purchases</h1>
        <br>
        <table>
            <thead>
                <tr>
                    <th colspan="2">Order Information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: left;">
                        <?php 
                        $orderNumber = rand(100, 999);
                        $date = date('Y-m-d');
                        $time = date('H:i:s');
                        ?>
                        <strong>Number: #</strong><?= $orderNumber; ?><br><br>
                        <strong>Date: </strong><?= $date; ?><br><br>
                        <strong>Time: </strong><?= $time; ?>
                    </td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th colspan="2">Purchased Products</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalPrice = 0;
                foreach($pastPurchases as $index => $purchase) {
                    $id = $purchase['id']; 
                    $selectAllProduct_query = "SELECT picture FROM products WHERE id = $id";
                    $result = mysqli_query($conn, $selectAllProduct_query);
                    $product = mysqli_fetch_assoc($result);
                    
                    // Check if the query was successful
                    if (isset($product)) {
                        $purchase['picture'] = $product['picture'];
                    } else {
                        $purchase['picture'] = '';
                    }
                ?>
                <tr>
                    <td><img src="data:image/jpeg;base64,<?php echo base64_encode($purchase['picture']); ?>" alt="<?php echo $purchase['product_name']; ?>"></td>
                    <td style="text-align: left;">
                        <strong>Name: </strong><?= isset($purchase['product_name']) ? $purchase['product_name'] : ''; ?><br><br>
                        <strong>Quantity: </strong><?= isset($purchase['quantity']) ? $purchase['quantity'] : ''; ?><br><br>
                        <strong>Price: </strong><?= isset($purchase['price']) ? $purchase['price'] . " SAR" : ''; ?>
                    </td>
                </tr>
                <!-- Update total price -->
                <?php 
                    if (isset($purchase['quantity']) && isset($purchase['price'])) {
                        $totalPrice += $purchase['quantity'] * $purchase['price'];
                    }
                ?>
                <?php } ?>
                <tr>
                    <th><strong>Total Price</strong></th>
                    <td colspan="2" style="text-align: center; background-color:  #5e544c1c;"><?php echo $totalPrice; ?> SAR</td>
                </tr>
            </tbody>
        </table>
        <?php else: ?>
    <h1>No past purchases available.</h1>
<?php endif; ?>
    </div>
<?php include('../include/footer.php'); ?>

</body>

</html>
