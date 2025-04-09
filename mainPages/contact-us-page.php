<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Page</title>
    <link rel="stylesheet" href="navPages-style.css"> 
</head>

<?php include('../include/header.php'); ?> 

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Truculenta:opsz,wght@12..72,100..900&display=swap" rel="stylesheet">

<body>
    <div class="container">
        <div class="feedback-form">
            <h1>Get in touch with us!</h1>
            <p>We are happy to receive any questions, concerns, or general feedback</p>
            <form id="contactForm">
                <div class="group">
                    <label>Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter Your Name" required>
                </div>
                <div class="group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter Your Email" required>
                </div>
                <div class="group">
                    <p>Feedback</p>
                    <textarea name="feedback" id="feedback" placeholder="Enter your feedback here..." required></textarea>
                </div>
                <div>
                    <button type="submit" name="submit" id="submit">Send</button>
                </div>
            </form>
        </div>
        <div class="visit-us">
            <h1>Come Visit Us</h1>
            <div>
                <p>Location: Mall of Dhahran Blvd, Al Dawhah Al Janubiyah, Dhahran 34457</p>
            </div>
            <div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3576.6390802423957!2d50.1693249!3d26.305797899999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e49e63110aa9a5f%3A0xa0df1dd460cea5d4!2sMall%20of%20Dhahran!5e0!3m2!1sen!2ssa!4v1711200269250!5m2!1sen!2ssa" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

    <?php include('../include/footer.php'); ?> 

    <script>
        document.getElementById("contactForm").addEventListener("submit", function(event) {
            event.preventDefault();
            alert("Thank you for your message! You will receive a response within a day.");
        });
    </script>
</body>
</html>
