
<?php 
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="form.css" />

   
</head>
<body>
            <div class="get-started">
            <a href="logout.php"> <span class="getsartedspan" style="font-family:sans-serif;font-size: 22px;font-weight: 600;" >Logout</span> </a>
            </div>
    </div><br><br><br><br><br>
    <br><br><br><br>


    <!-- Contact Form -->
    <div class="ajent-box-outer1" style="position: relative; ">
            <div class="ajent-box-inner">
                <div class="inner_with_main_content">
                    <div class="inner_box1">
                        <p><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d735.2383784093411!2d77.3300495812755!3d19.179060866396036!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd1d66bb9d31721%3A0x43892c180155daf1!2sNanded%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1716314783654!5m2!1sen!2sin" width="480" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></p>
                    </div>
                    <div class="inner_box2">
                        <h1 class="contact-para1" style="text-align: center;">
                            Contact Me
                        </h1>

                        <form action="contact.html" method="post">
                            <input type="text" placeholder="Username" class="contact-form" id="contact-username" name="contact-username">

                            <input type="email" placeholder="Email" class="contact-form" id="contact-Email" name="contact-Email">
                        <br><br>
                            <input type="text" placeholder="Subject" class="contact-form-subject" id="contact-Subject" name="contact-Subject">
                            <br><br>
                           <textarea placeholder="Message" class="contact-message" id="contact-message" name="contact-message"></textarea>
                           <br><br>
                           <input type="submit" name="submit" id="submit" value="Send Message" class="contact-submit" >
                        </form>
                    </div>
                </div>
            </div>
    </div>
</body>
</html>

