    <?php include 'config/database.php';
    session_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NatureGear - Your Outdoor Gear Solution</title>
        <link rel="icon" href="images/logo.png" type="image/x-icon">
        <meta name="description"
            content="NatureGear offers top-quality outdoor gear rental, cleaning, and sales services. Explore our wide range of products and services today.">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
            integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer">
        <link rel="stylesheet" href="./styles/index.css">
    </head>

    <body>
        <?php include 'assets/navbar.php'; ?>

        <main>
            <section class="home">
                <div class="slideshow-container">
                    <!-- Slide 1 -->
                    <div class="mySlides fade">
                        <img src="./images/indexbg1.png" style="width:100%" alt="NatureGear Outdoor Gear">
                        <div class="slide-text">
                            <h1>Welcome to NatureGear</h1>
                            <p>Your one-stop solution for outdoor gear rental, cleaning, and sales.</p>
                            <div class="naturegear-box-container">
                                <a href="#">Explore More</a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="mySlides fade">
                        <img src="./images/indexbg2.png" style="width:100%" alt="Why Choose Us?">
                        <div class="slide-text">
                            <h1>Kenapa Kami?</h1>
                            <p>Kami menyediakan peralatan outdoor terbaik dengan kualitas terjamin dan harga terjangkau.
                                Semua ada disini akses melalui tombol di bawah ini.</p>
                            <div class="naturegear-box-container">
                                <a href="my-account.php">Pemesanan</a>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="mySlides fade">
                        <img src="./images/indexbg3.png" style="width:100%" alt="Our Advantages">
                        <div class="slide-text">
                            <h1>Keunggulan Kami</h1>
                            <p>Kami memiliki pengalaman bertahun-tahun dalam menyediakan peralatan outdoor berkualitas
                                tinggi kepada pelanggan kami.
                                <?php
                                if (isset($_SESSION['username'])) {
                                    echo "Nikmati pengalaman terbaik dengan NatureGear!";
                                } else {
                                    echo "Segera daftarkan diri anda disini.";
                                }
                                ?>
                            </p>
                            <div class="naturegear-box-container">
                                <?php
                                if (isset($_SESSION['username'])) {
                                    echo "<a href='#'>Enjoy!</a>";
                                } else {
                                    echo "<a href='my-account.php'>Daftar Disini!</a>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Next and previous buttons -->
                    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                    <a class="next" onclick="plusSlides(1)">&#10095;</a>

                    <!-- The dots/bullets/indicators -->
                    <div class="dot-container">
                        <span class="dot" onclick="currentSlide(1)"></span>
                        <span class="dot" onclick="currentSlide(2)"></span>
                        <span class="dot" onclick="currentSlide(3)"></span>
                    </div>
                </div>
                <br>

                <!-- Konten Layanan -->
                <!-- Konten Layanan -->
                <div id="services" class="content">
                    <h2>Our <span>Services</span></h2>
                    <div class="services">
                        <div class="service">
                            <h3>Gear Cleaning</h3>
                            <p>Pembersihan profesional untuk semua peralatan outdoor Anda agar tetap dalam kondisi terbaik
                                dan balik seperti semula.</p>
                            <p>Mulai dari Rp<?php echo number_format(10000, 0, ',', '.'); ?></p>
                            <a href="gear-cleaning.php" class="service-link">Learn More</a>
                        </div>
                        <div class="service">
                            <h3>Equipment Rental</h3>
                            <p>Penyewaan peralatan berkualitas tinggi untuk petualangan outdoor Anda, mulai dari tenda
                                hingga sepatu hiking.</p>
                            <p>Mulai dari Rp<?php echo number_format(2000, 0, ',', '.'); ?>/hari</p>
                            <a href="equipment-rental.php" class="service-link">Learn More</a>
                        </div>
                    </div>
                </div>


                <!-- Konten Produk -->
                <div id="products" class="content">
                    <h2>Our <span>Products</span></h2>
                    <div class="products">
                        <?php
                        $sql = "SELECT id, name, description, price, image FROM products LIMIT 3";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='product'>";
                                echo "<img src='" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["name"]) . "'>";
                                echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                                echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                                echo "<p>Rp" . number_format($row["price"], 0, ',', '.') . "</p>";
                                if (isset($_SESSION['username'])) {
                                    echo "<form action='add-to-shopping-cart.php' method='POST'>";
                                    echo "<input type='hidden' name='product_id' value='" . $row["id"] . "'>";
                                    echo "<input type='hidden' name='product_name' value='" . $row["name"] . "'>";
                                    echo "<input type='hidden' name='product_price' value='" . $row["price"] . "'>";
                                    echo "<input type='hidden' name='product_img' value='" . $row["image"] . "'>";
                                    echo "<button type='submit' name='add_to_cart' class='add-cart'><i class='fas fa-cart-plus'></i></button>";
                                    echo "</form>";
                                } else {
                                    echo "<a href='my-account.php' class='add-cart'><i class='fas fa-cart-plus'></i> Login to add to cart</a>";
                                }
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No products available</p>";
                        }
                        ?>
                    </div>
                    <div class="see-more">
                        <a href="products.php">See More</a>
                    </div>
                </div>

                <!-- Konten Reviews -->
                <div id="reviews" class="content">
                    <h2>Our <span>Reviews</span></h2>
                    <div class="reviews">
                        <?php
                        $stm = $pdo->query("SELECT * FROM `reviews` ORDER by `id` DESC LIMIT 3");
                        while ($fetch = $stm->fetch()) {
                            ?>
                            <div class="review-box-container">
                                <div class="review-box">
                                    <div class="box-top">
                                        <div class="profile">
                                            <div class="profile-img">
                                                <img src="images/logo.png" alt="Profile Image">
                                            </div>
                                            <div class="name-user">
                                                <strong><?= htmlspecialchars($fetch['nama']); ?></strong>
                                                <span>@<?= htmlspecialchars($fetch['username']); ?></span>
                                            </div>
                                        </div>
                                        <div class="time">
                                            <strong><?= htmlspecialchars($fetch['tanggal']); ?></strong><br>
                                            <span><?= htmlspecialchars($fetch['jam']); ?></span>
                                        </div>
                                    </div>
                                    <div class="client-comment">
                                        <p><?= htmlspecialchars($fetch['komentar']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="see-more">
                        <a href="review.php">See More</a>
                    </div>
            </section>
        </main>

        <?php include 'assets/footer.php'; ?>
        <script src="js/index.js"></script>

    </body>

    </html>