<?php
include ('config/database.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - Contact Us</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="styles/contact-us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body>
    <!-- Navbar Section -->
    <?php include ('assets/navbar.php'); ?>
    <!-- Navbar Section End -->

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h1 class="heading top-top">Contact <span>Us</span></h1>
        <div class="container">
            <a href="mailto:naturegear@gmail.com">
                <div class="contact-box">
                    <div>
                        <h2>
                            Email<br>
                            <span>Kirim email kapanpun</span>
                        </h2>
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
            </a>

            <a href="https://goo.gl/maps/naturegear" target="_blank" rel="noopener noreferrer">
                <div class="contact-box">
                    <div>
                        <h2>
                            Alamat<br>
                            <span>Jumpai kami langsung</span>
                        </h2>
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                </div>
            </a>

            <a href="https://www.instagram.com/naturegear/" target="_blank" rel="noopener noreferrer">
                <div class="contact-box">
                    <div>
                        <h2>
                            Instagram<br>
                            <span>Ikuti kami di Instagram</span>
                        </h2>
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                </div>
            </a>

            <a href="https://www.facebook.com/naturegear" target="_blank" rel="noopener noreferrer">
                <div class="contact-box">
                    <div>
                        <h2>
                            Facebook<br>
                            <span>Ikuti kami di Facebook</span>
                        </h2>
                        <i class="fa-brands fa-facebook"></i>
                    </div>
                </div>
            </a>

            <a href="https://www.twitter.com/naturegear" target="_blank" rel="noopener noreferrer">
                <div class="contact-box">
                    <div>
                        <h2>
                            Twitter<br>
                            <span>Ikuti kami di Twitter</span>
                        </h2>
                        <i class="fa-brands fa-twitter"></i>
                    </div>
                </div>
            </a>
        </div>
    </section>
    <!-- Contact Section End -->
    
    <!-- Footer Section -->
    <?php include ('assets/footer.php'); ?>
    <!-- Footer Section End -->

</body>

</html>
