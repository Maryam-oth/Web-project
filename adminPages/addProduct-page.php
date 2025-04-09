<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['username'])) {
    header('location: ../mainPages/login-page.php'); // Redirect to the login page
    exit(); // Stop further execution
}

include('../include/connection.php');

$name = $Description = $Price = $Stock = ""; // Initialize variables

if (isset($_POST['addProduct'])) {
    $name = mysqli_real_escape_string($conn, $_POST['ProductName']);
    $Description = mysqli_real_escape_string($conn, $_POST['ProductDescription']);
    $Price = mysqli_real_escape_string($conn, $_POST['ProductPrice']);
    $Stock = mysqli_real_escape_string($conn, $_POST['ProductStock']);

    // Default image path
    $defaultImagePath = '../assets/image-Icon.png';

    // File upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $Image_tmp_name = $_FILES['image']['tmp_name'];
        $Image_type = $_FILES['image']['type'];
        $Image_folder = '../assets/products-Img/';
        $Image = addslashes(file_get_contents($Image_tmp_name)); // Define $Image variable here

        // Check the file type
        if ($Image_type == 'image/jpeg' || $Image_type == 'image/jpg' || $Image_type == 'image/png') {
            // Insert the image data into the database
            $insertProduct_query = "INSERT INTO products (name, price, stock, description, picture, admin_id) 
                                    VALUES ('$name', '$Price', '$Stock', '$Description', '$Image', '{$_SESSION['admin_id']}')";
        } else {
            $_SESSION['message'] = "Error: Unsupported file format! Please upload a .jpg, .jpeg, or .png file.";
        }
    } else {
        // Use default image if no file is uploaded
        $Image = addslashes(file_get_contents($defaultImagePath));
        $insertProduct_query = "INSERT INTO products (name, price, stock, description, picture, admin_id) 
                                VALUES ('$name', '$Price', '$Stock', '$Description', '$Image', '{$_SESSION['admin_id']}')";
    }

    // Execute the query
    if (isset($insertProduct_query)) {
        if (mysqli_query($conn, $insertProduct_query)) {
            $_SESSION['message'] = "Product has been added successfully!";
            if (isset($Image_tmp_name)) {
                $destination = $Image_folder . basename($_FILES['image']['name']);
                move_uploaded_file($Image_tmp_name, $destination);
            }
        } else {
            $_SESSION['message'] = "Error: Couldn't add product!";
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thameen Jewelry - Add a New Product</title>
    <link rel="stylesheet" href="adminPages-Style.css"> 
    <?php include ('../include/fonts.html'); ?>
<body class="bodyOfAdminPages">
    <?php include ('../include/adminHeader.php'); ?>
    <div class="container-between-header-footer">
        <h1 class="mainTitle">Add Product</h1>
        <?php include '../adminPages/messsage.php'; ?>
        <form method="post" action="" class="adminForm" enctype="multipart/form-data">
            <p class="sub-title">Product Detail</p>
            
            <label for="ProductName" class="adminformlabel">Name</label>
            <input type="text" name="ProductName" id="ProductName" class="adminforminput" value="<?php echo htmlspecialchars($name); ?>" autofocus required>
            
            <label for="ProductDescription" class="adminformlabel">Description</label>
            <textarea name="ProductDescription" id="ProductDescription" class="edit-Add-page-textarea" rows="5"><?php echo htmlspecialchars($Description); ?></textarea>
            
            <label for="ProductPrice" class="adminformlabel">Price</label>
            <input type="number" min="1" name="ProductPrice" id="ProductPrice" class="adminforminput" value="<?php echo htmlspecialchars($Price); ?>">
            
            <label for="ProductStock" class="adminformlabel">Stock</label>
            <input type="number" min="1" name="ProductStock" id="ProductStock" class="adminforminput" value="<?php echo htmlspecialchars($Stock); ?>">
            
            <label for="ProductImage" class="adminformlabel">Image</label>
            <div class="pageImage-container">
                <img id="productImage" src="..\assets\image-Icon.png" alt="Preview Image">
            </div>

            <input type="file" id="ProductImage" name="image" accept="image/jpg, image/jpeg, image/png" onchange="previewImage(event)">

            <input class="secondAddButton" type="submit" name="addProduct" value="Add">
            <input class="BackButton" type="button" value="Back" onclick="window.location.href='manageProduct-page.php'">
        </form>
    </div>
    <?php include('../include/footer.php'); ?>

    <!-- JavaScript to read and display the selected image in the preview container -->
    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                const img = document.getElementById('productImage');
                img.src = reader.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    </script>

</body>
</html>
