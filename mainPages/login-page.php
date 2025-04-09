<?php
// Check if a session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../include/connection.php');

// Initialize the error variable
$error_message = '';

if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == 'POST') { // Ensure the form is submitted using POST
    $uname = $_POST['username'];
    $password = $_POST['password'];
    $captcha_input = $_POST['captcha']; // Added captcha input field

    // Query admin table
    $admin_sql = "SELECT * FROM `admin` WHERE `username` = '$uname' AND `password` = '$password'";
    $admin_result = mysqli_query($conn, $admin_sql);
    $admin_row = mysqli_fetch_assoc($admin_result);

    if ($admin_row && $captcha_input == $_SESSION['captcha']) { // Added captcha validation condition
        $_SESSION['password'] = $admin_row['password'];
        $_SESSION['username'] = $admin_row['username'];

        if ($admin_row['is_admin'] == 1) { // Check if user is an admin
            header('Location: ../adminPages/manageProduct-page.php');
            exit(); // Always exit after redirecting
        } else {
            header('Location: ../adminPages/manageProduct-page.php');
            exit();
        }
    } else {
        $error_message = 'Invalid username, password, or captcha. Please try again.';
    }
}

// Captcha generation logic
$captcha = ""; // Initialize captcha variable
$characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; // Characters for captcha
for ($i = 0; $i < 5; $i++) { // Generate 5-character captcha
    $captcha .= $characters[rand(0, strlen($characters) - 1)];
}
$_SESSION['captcha'] = $captcha; // Store captcha in session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login-style.css">
    <?php include("../include/fonts.html"); ?>
</head>

<body>
    <?php include('../include/header.php'); ?>
    <div class="login-container">
        <?php if ($error_message): ?>
            <div class="error-message" id="error-message">
                <img src="../assets/confirmMessage-Icon/errorIcon.png" width="16" height="16">
                <?php echo $error_message; ?>
                <button type="button" class="close-button" onclick="dismissError()">&times;</button>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <h2>Login</h2>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter Your Username" required autofocus>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Your Password" required>
                <label for="captcha">Captcha</label>
                <div class="captcha-container"> <!-- Captcha display area -->
                    <div class="captcha-display" id="captcha-display"><?php echo $captcha; ?></div>
                    <input type="text" id="captcha" name="captcha" placeholder="Enter Captcha" required>
                </div>
                <input type="submit" class='btn' value='Login' name='login'>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        function dismissError() {
            document.getElementById('error-message').style.display = 'none';
        }
    </script>
    <?php include('../include/footer.php'); ?>
</body>

</html>
