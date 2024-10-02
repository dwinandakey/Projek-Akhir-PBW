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
    <title>NatureGear - About Us</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="./styles/about-us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body>
    <!-- Navbar Section -->
    <?php include ('assets/navbar.php'); ?>
    <!-- Navbar Section End -->

    <div class="about-us-container">
        <h1>About <span>Us</span></h1>
        <p>Selamat datang di Situs Kami! Kami berdedikasi untuk menyediakan produk dan layanan terbaik untuk Anda.</p>
        <h1>Our <span>Mission</span></h1>
        <p>Misi kami adalah memberikan produk berkualitas tinggi yang memberikan kebahagiaan dan kepuasan kepada
            pelanggan kami.</p>
        <h1>Our <span>Story</span></h1>
        <p>Selama lebih dari satu dekade, NatureGear telah dengan teliti menguji berbagai macam peralatan outdoor, mulai
            dari ransel hingga tenda, kompor, dan banyak produk lainnya. Misi utama kami di NatureGear adalah untuk
            menumbuhkan semangat yang mendalam akan petualangan di alam terbuka!</p>
        <p>Kami adalah bisnis yang dimiliki dan dioperasikan oleh keluarga yang berkomitmen untuk membuat pengalaman
            outdoor premium dapat diakses oleh semua orang. Untuk memastikan petualangan outdoor Anda tidak kurang dari
            luar biasa, kami hanya menawarkan peralatan terbaik yang tersedia.</p>
        <p>Program-program kami memberikan kesempatan yang sangat baik untuk mempersiapkan diri untuk petualangan
            berikutnya Anda. NatureGear hadir sebagai sumber utama Anda untuk segala sesuatu tentang berkemah dan
            backpacking.</p>
        <p>Dibentuk pada tahun 2024 oleh seorang penggemar outdoor yang frustasi dengan biaya peralatan yang sangat
            tinggi yang jarang digunakan, NatureGear dengan cepat berkembang dari beberapa toko di pasar online menjadi
            platformnya sendiri, menawarkan berbagai macam peralatan.</p>
        <p>NatureGear mencerminkan semangat petualangan - di mana pun dan kapan pun. Kami adalah kolektif pendaki,
            pendaki gunung, pendaki, pemanjat, penjelajah belakang, dan lainnya. Kami tidak hanya menjual peralatan;
            kami menggunakannya, kami percaya padanya, dan kami mendukungnya.</p>
        <p>Lebih lanjut lagi, NatureGear telah berkembang menjadi NatureGear Events, menyediakan dukungan untuk
            balapan petualangan, regatta, lomba dan acara lainnya. Untuk kebutuhan acara Anda, hubungi kami di:
            naturegear.com.</p>
        <h1>Our <span>Team</span></h1>
        <p>Kami memiliki tim yang beragam dari para ahli yang bersemangat tentang apa yang mereka lakukan. Temui anggota
            tim kami:</p>
        <div class="row">
            <div class="column">
                <div class="card">
                    <img src="./images/nanda.jpg" alt="Nanda" style="width:100%">
                    <div class="container">
                        <strong>Dwinanda Muhammad Keyzha</strong>
                        <p class="title">CEO & Founder</p>
                        <p>222212576@stis.ac.id</p>
                        <p><a href="mailto:222212576@stis.ac.id"><button class="button">Contact</button></a></p>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="card">
                    <img src="./images/dwinanda.jpg" alt="Dwinanda" style="width:100%">
                    <div class="container">
                        <strong>Dwinanda Muhammad Keyzha</strong>
                        <p class="title">Head of Marketing</p>
                        <p>222212576@stis.ac.id</p>
                        <p><a href="mailto:222212576@stis.ac.id"><button class="button">Contact</button></a></p>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="card">
                    <img src="./images/trinanda.jpg" alt="Trinanda" style="width:100%">
                    <div class="container">
                        <strong>Dwinanda Muhammad Keyzha</strong>
                        <p class="title">Lead Developer</p>
                        <p>222212576@stis.ac.id</p>
                        <p><a href="mailto:222212576@stis.ac.id"><button class="button">Contact</button></a></p>
                    </div>
                </div>
            </div>
        </div>
        <a href="products.php"><button class="btn" id="btn-menu">Pesan Sekarang</button></a>
    </div>


    <!-- Footer Section -->
    <?php include ('assets/footer.php'); ?>
    <!-- Footer Section End -->

</body>

</html>