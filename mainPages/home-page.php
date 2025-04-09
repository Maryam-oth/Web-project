
<!DOCTYPE html>
<html>
<head>
<title>THAMEEN</title>
<link rel="stylesheet" href="home.css">
<?php include("../include/fonts.html"); ?>
</head>

<?php
    include('../include/header.php')
  ?>

<body>
    <secion>
    <div id = "slider">
        <figure>
            <img src="../assets/main-Img/P1.png">
            <img src = "../assets/main-Img/P2.png">
            <img src = "../assets/main-Img/P3.png">
            <img src="../assets/main-Img/P1.png">
        </figure>
    </div>
    </secion>
    <section class = "shop-now">
        <div >
            <h1>Shop The latest of Our Jewelry Collection</h1>

        </div>
        <div style="padding-bottom: 20px;">
            <a href="../productsPages/products-page.php" class="btnn">Start Shopping</a>
        </div>
        
    </section>
    <section id="about" class = "about-us">
        <div>
            <h1>About Us</h1>
        </div>
        <div id="about">
            <p>At THAMEEN, we maintain that jewelry is more than simply decoration, it's a way to express one's uniqueness, show off one's sense of style, and celebrate life's significant moments. We have dedicated ourselves to providing fine jewelry items that embody sophistication, elegance, and eternal beauty ever since our beginning.</p>
        </div>
    </section>

    <?php
    include('../include/footer.php')
  ?>
</body>
</html>