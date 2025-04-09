<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if user is not logged in
if (!isset($_SESSION['username'])) {
    header('location: ../mainPages/login-page.php'); // Redirect to the login page
    exit(); // Stop further execution
}

$_UnsupportedFileFormate = false;

include('../include/connection.php');

if (isset($_POST['saveButton'])) {
    $product_ID = mysqli_real_escape_string($conn, $_SESSION['product_ID']);
    $name = mysqli_real_escape_string($conn, $_POST['ProductName']);
    $Description = mysqli_real_escape_string($conn, $_POST['ProductDescription']);
    $Price = mysqli_real_escape_string($conn, $_POST['ProductPrice']);
    $Stock = mysqli_real_escape_string($conn, $_POST['ProductStock']);

    // File upload handling
    if (isset($_FILES['ProductImage']) && $_FILES['ProductImage']['error'] === UPLOAD_ERR_OK) {
        $Image_tmp_name = $_FILES['ProductImage']['tmp_name'];
        $Image_type = $_FILES['ProductImage']['type'];
        $Image_folder = '../assets/products-Img/';

        // Check the file type
        if ($Image_type == 'image/jpeg' || $Image_type == 'image/jpg' || $Image_type == 'image/png') {
            // Read image file as binary data
            $Image = addslashes(file_get_contents($Image_tmp_name)); // Define $Image variable here

            // Update the image data into the database
            $editProduct_query = "UPDATE products SET name='$name', description='$Description', price='$Price', stock='$Stock', picture='$Image' WHERE id='$product_ID'";
        } else {
            $_SESSION['message'] = "Error: Unsupported file format! Please upload a .jpg, .jpeg, or .png file.";
            $_UnsupportedFileFormate = true;
        }
    } else {
        // Update other product details except the image
        $editProduct_query = "UPDATE products SET name='$name', description='$Description', price='$Price', stock='$Stock' WHERE id='$product_ID'";
    }

    // Execute the query
    if (isset($editProduct_query)) {
        if (mysqli_query($conn, $editProduct_query)) {
            $_SESSION['message'] = "Product has been edited successfully!";
            if (isset($Image_tmp_name)) {
                $destination = $Image_folder . basename($_FILES['ProductImage']['name']);
                move_uploaded_file($Image_tmp_name, $destination);
            }
        } else {
            $_SESSION['message'] = "Error: Couldn't edit product!";
        }
        header("Location: manageProduct-page.php");
        exit();
        //mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thameen Jewelry - Edit Product Detail</title>
    <link rel="stylesheet" href="adminPages-Style.css"> <!-- Link to external stylesheet -->
</head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Truculenta:opsz,wght@12..72,100..900&display=swap" rel="stylesheet">

<body class="bodyOfAdminPages">
    <?php include('../include/adminHeader.php'); ?>

    <div class="container-between-header-footer">
        <h1 class="mainTitle">Edit Product Detail</h1>
        <?php
        if ($_UnsupportedFileFormate) {
            include '../adminPages/messsage.php';
        }
        include('../include/connection.php');

        if (isset($_GET['id'])) {
            $product_ID = mysqli_real_escape_string($conn,  $_GET['id']);
            $_SESSION['product_ID'] = $product_ID;
            $SelectProduct_query = "SELECT * FROM products WHERE id='$product_ID'";

            //Faild in executing select a specific product query
            if (!($SelectProduct_queryResult = mysqli_query($conn, $SelectProduct_query))) {
                $_SESSION['message'] = "Error, Couldn't be able to retrive the product information that you wish to Modify!";
            } elseif (mysqli_num_rows($SelectProduct_queryResult) > 0) { // check if the selected proudect is in the database
                $selectedProdect = mysqli_fetch_array($SelectProduct_queryResult);
        ?>
                <form method="post" action="" class="adminForm" enctype="multipart/form-data"> <!-- the white contaner-->
                    <p class="sub-title">Product Detail</p>

                    <label for="ProductName" class="adminformlabel">Name</label>
                    <input type="text" name="ProductName" id="ProductName" class="adminforminput" autofocus value="<?= $selectedProdect['name'] ?>">


                    <label for="ProductDescription" class="adminformlabel">Description</label>
                    <textarea name="ProductDescription" id="ProductDescription" class="edit-Add-page-textarea" rows="5"> <?= $selectedProdect['description'] ?></textarea>

                    <label for="ProductPrice" class="currency-label">Price</label>
                    <input type="number"  name="ProductPrice" min="1" id="ProductPrice" class="adminforminput" value="<?= $selectedProdect['price'] ?>" placeholder="Price">

                    <label for="ProductStock" class="adminformlabel">Stock</label>
                    <input type="number" name="ProductStock" min="1" id="ProductStock" class="adminforminput" value="<?= $selectedProdect['stock'] ?>">

                    <label for="ProductImage" class="adminformlabel">Image</label>
                    <div class="pageImage-container">
                        <img id="productImage" src="data:image/jpeg;base64,<?php echo base64_encode($selectedProdect['picture']); ?>" alt="<?php echo $selectedProdect['name']; ?>">
                    </div>

                    <input type="file" name="ProductImage" id="ProductImage" accept="image/jpg, image/jpeg, image/png">

                    <!-- Actions button-->
                    <input class="saveButton" name="saveButton" type="submit" value="Save">
                    <input class="BackButton" type="button" value="Back" onclick="window.location.href='../adminPages/manageProduct-page.php'">

                </form>
        <?php
            } else {
                $_SESSION['message'] = "Error, no such product of id=$product_ID found in the database!";
            }
        } else {
            $_SESSION['message'] = "Error, Couldn't be able to identify the product that you wish to Modify!";
        }
        if (!($_UnsupportedFileFormate)) //other erorr 
            include '../adminPages/messsage.php';
        ?>
    </div>

    <?php include('../include/footer.php'); ?>

    <!-- to change the image to the new selected one-->
    <script>
        // Get the file input element
        const productImageInput = document.getElementById('ProductImage');
        // Get the image element
        const productImage = document.getElementById('productImage');

        // Add change event listener to file input
        productImageInput.addEventListener('change', function() {
            const file = this.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                // Update the src attribute of the image element
                productImage.src = e.target.result;
            }

            reader.readAsDataURL(file);
        });
    </script>

</body>

</html>