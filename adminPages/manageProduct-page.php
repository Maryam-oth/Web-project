<?php 

session_start();

// Check if user is not logged in
if(!isset($_SESSION['username'])) { 
    header('location: ../mainPages/login-page.php'); // Redirect to the login page
    exit(); // Stop further execution

} 

$username = $_SESSION['username'];
$fetchAdminFname_query = "SELECT Fname FROM thameen.admin WHERE username = '$username'";
$fetchAdminID_query = "SELECT id FROM thameen.admin WHERE username = '$username'";

include('../include/connection.php');

//Faild in executing fetch admin first name query
if(!($fetchAdminFname_queryResult = mysqli_query($conn, $fetchAdminFname_query))) {
    $_SESSION['admin_Fname'] = "Unknown"; //Unknown admin Fname 
}

// Fetch the admin First name from the array
if(mysqli_num_rows($fetchAdminFname_queryResult) > 0) {
    $_SESSION['admin_Fname'] = mysqli_fetch_array($fetchAdminFname_queryResult)[0]; 
}
else{
    $_SESSION['admin_Fname'] = "Unknown"; //Unknown admin Fname 
}

//Faild in executing fetch admin id query
if(!($fetchAdminID_queryResult = mysqli_query($conn, $fetchAdminID_query))) {
    $_SESSION['admin_id']=0; //Unknown admin ID 
}

// Fetch the admin ID from the array
if(mysqli_num_rows($fetchAdminID_queryResult) > 0) {
    $_SESSION['admin_id']= mysqli_fetch_array($fetchAdminID_queryResult)[0]; 
}
else{
    $_SESSION['admin_id']=0; //Unknown admin ID 
}



if(isset($_POST['deleteButton']))
{
    $product_ID = mysqli_real_escape_string($conn, $_POST['product_ID']); // Use POST method here

    $deleteProduct_query = "DELETE FROM products WHERE id='$product_ID' ";

    if(!($deleteProduct_queryResulte = mysqli_query($conn, $deleteProduct_query)))
    {
        $_SESSION['message'] = "Error, couldn't be able to delete the product!";
        header("Location: manageProduct-page.php");
    }
    else
    {
        $_SESSION['message'] = "Product has been Deleted Successfully!";
        header("Location: manageProduct-page.php");
    }
    exit(0);
    //mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thameen Jewelry - Product Management</title>
    <link rel="stylesheet" href="adminPages-Style.css"> <!-- Link to external stylesheet -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Truculenta:opsz,wght@12..72,100..900&display=swap" rel="stylesheet">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bodyOfAdminPages">
    <?php include ('../include/adminHeader.php'); ?>
    <div class="container-between-header-footer">
        <?php 
            $selectAllProduct_query = "SELECT * FROM products";
            //Faild in executing select all products query
            if(!($selectAllProduct_queryResult = mysqli_query($conn, $selectAllProduct_query))) {
                $_SESSION['message'] = "Error, Couldn't be able to retrive the products";
                include '../adminPages/messsage.php'; 
                mysqli_close($conn);  
                exit(0);

            } 

            $total=mysqli_num_rows($selectAllProduct_queryResult);
        ?>   
        <?php include '../adminPages/messsage.php';  ?>
        <div id="White-Container">
            <div id="white-Container-head"> 
                
                <h1 class="mainTitle">Product list | <span class="spanTitle"> <?= "Total= $total"?> </span></h1> 
                <div class="font">
                    <input type="search" class="searchInput-AdminPage" id="search_text" placeholder="Search Product.." >
                    <input type="submit" value="   Add Product   " onclick="window.location.href='addProduct-page.php'" class="firstAddButton">
                </div>
            </div>
            <table id="manageProductTable">
                <thead id="theadOfmanageProductTable">
                    <tr> <!-- first row in the table (head) -->
                        <th class="tobLeftRedius-ProductTable">S.N.</th>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th class="tobRightRedius-ProductTable">Actions</th>
                    </tr>
               </thead>
                <tbody id="tbodyOfmanageProductTable">
                    <?php 
                        if(mysqli_num_rows($selectAllProduct_queryResult) > 0) {
                            $count = 1;
                            foreach($selectAllProduct_queryResult as $product) {
                    ?>   
                        <tr>
                            <td><?= $count ?></td>
                            <td><img src="data:image/jpeg;base64,<?php echo base64_encode($product['picture']); ?>" alt="<?php echo $product['name']; ?>"></td>
                            <td><?= $product['name']; ?></td>
                            <td><?= $product['description']; ?></td>
                            <td><?= $product['price']; ?> SAR</td>
                            <td><?= $product['stock']; ?></td>
                            <td>
                                <form method="post" action="" style="all: initial">
                                    <input type="hidden" name="product_ID" value="<?= $product['id']; ?>">
                                    <input type="submit" name="deleteButton" value="Delete" class="deleteButton" onclick="return confirmDelete('<?= $product['name']; ?>');">

                                </form>
                                <form action="editProduct-page.php" method="get" style="all: initial">
                                    <input type="hidden" name="id" value="<?= $product['id']; ?>">
                                    <input type="submit" value="Edit" class="editButton">
                                </form>
                            </td>
                        </tr>
                    <?php
                            $count++;
                            }
                        } else {
                            echo "<tr><td colspan='7'>No Products Found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include('../include/footer.php'); ?>

    <!-- JavaScript for doing the search functionality-->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#search_text").keyup(function(){
                var search = $(this).val();
                $.ajax({
                    url: 'searchCode.php',
                    method: 'post',
                    data: {query: search},
                    success: function(response){
                        $("#manageProductTable").html(response);
                    }
                });
            });
        });
$(document).ready(function() {
    $("#search_text").keyup(function(){
        var search = $(this).val();
        $.ajax({
            url: 'searchCode.php',
            method: 'post',
            data: {query: search},
            success: function(response){
                $("#manageProductTable").html(response);
            }
        });
    });

    //  Event listener for input field value change 
    $('#search_text').on('input', function() {
        var search = $(this).val().trim(); // Trim any leading or trailing whitespace
        if (search === '') {
            // If search input is empty, display all products 
            $.ajax({
                url: 'searchCode.php',
                method: 'post',
                success: function(response){
                    $("#manageProductTable").html(response);
                }
            });
        }
    });
});

        </script>

<!-- JavaScript function to show a confirmation dialog before deleting a product -->
   <script type="text/javascript">
        function confirmDelete(productName) {
            return confirm("Are you sure you want to delete " + productName + "?");
        }
    </script> 


</body> 
</html>

