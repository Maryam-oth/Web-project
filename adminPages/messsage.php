<?php
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $isError = stripos($message, "Error"); // Check if the message contains the word "Error" (case-insensitive)

    // Define styles based on whether it's an error message or not
    $borderColor = $isError !== false ? '#dc3545' : '#28a745';
    $backgroundColor = $isError !== false ? '#f8d7da' : '#d4edda';
    $textColor = $isError !== false ? '#721c24' : '#155724';
?>

<div id="messageContainer" style="position: relative; margin:5px; padding: 20px; border: 1px solid <?php echo $borderColor; ?>; background-color: <?php echo $backgroundColor; ?>; color: <?php echo $textColor; ?>; border-radius: 0.25rem;">
    <div style="position: relative; display: flex; align-items: center;">
        <img src="<?php echo $isError !== false ? '../assets/confirmMessage-Icon/errorIcon.png' : '../assets/confirmMessage-Icon/checkIcon.png'; ?>" width="16" height="16" style="margin-right: 5px;">
        <?= $message; ?>
        <input type="button" onclick="closeMessage()" class="btn-close" value="&times;" aria-label="Close" style="padding: 0; width: 30px; height: 30px; font-size: 24px; line-height: 1; text-align: center; cursor: pointer; border: none; background: transparent; color: #000000;">
    </div>
</div>

<script>
    function closeMessage() {
        // Get a reference to the message container
        var messageContainer = document.getElementById("messageContainer");
        // Hide the message container
        messageContainer.style.display = "none";
    }
</script>

<?php 
unset($_SESSION['message']);
}
?>

