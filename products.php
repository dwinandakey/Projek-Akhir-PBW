<?php include 'config/database.php';

// Query untuk mendapatkan data produk
$sql = "SELECT id, name, description, price, image FROM products";
$result = $conn->query($sql);
$productsToDisplay = [];

if ($result->num_rows > 0) {
    // Menambahkan produk ke dalam array
    while ($row = $result->fetch_assoc()) {
        $productsToDisplay[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - Products</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="styles/products.css">
</head>

<body>
    <?php include 'assets/navbar.php'; ?>
    <main>
        <h2>Our <span>Products</span></h2>
        <div class="sub-header">
            <div class="jenis" id="camping-gear">
                <i class="fas fa-campground"></i>
                <p>Camping Gear</p>
            </div>
            <div class="jenis" id="hiking-gear">
                <i class="fas fa-hiking"></i>
                <p>Hiking Gear</p>
            </div>
            <div class="search-box">
                <input class="cari" type="text" placeholder="Find equipment..." />
                <div class="search-btn">
                    <i class="fas fa-search"></i>
                </div>
                <div class="cancel-btn">
                    <i class="fas fa-times"></i>
                </div>
                <div class="search-data"></div>
            </div>
        </div>
        <section class="products">
            <div class="product-container">
                <?php
                foreach ($productsToDisplay as $product) {
                    echo "<div class='product'>";
                    echo "<img src='" . htmlspecialchars($product["image"]) . "' alt='" . htmlspecialchars($product["name"]) . "'>";
                    echo "<h3>" . htmlspecialchars($product["name"]) . "</h3>";
                    echo "<p>" . htmlspecialchars($product["description"]) . "</p>";
                    echo "<p class='price'>Rp" . number_format($product["price"], 0, ',', '.') . "</p>";
                    if (isset($_SESSION['username'])) {
                        echo "<form action='add-to-shopping-cart.php' method='POST'>";
                        echo "<input type='hidden' name='product_id' value='" . $product["id"] . "'>";
                        echo "<input type='hidden' name='product_name' value='" . $product["name"] . "'>";
                        echo "<input type='hidden' name='product_price' value='" . $product["price"] . "'>";
                        echo "<input type='hidden' name='product_img' value='" . $product["image"] . "'>";
                        echo "<button type='submit' name='add_to_cart' class='add-cart'><i class='fas fa-cart-plus'></i>Add to Cart</button>";
                        echo "</form>";
                    } else {
                        echo "<a href='my-account.php' class='add-cart'><i class='fas fa-cart-plus'></i> Login to add to cart</a>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    </main>

    <?php include 'assets/footer.php'; ?>
    <script src="js/products.js"></script>
</body>

</html>