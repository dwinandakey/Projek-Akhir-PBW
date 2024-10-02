<?php
session_start();
include ('config/database.php');

try {
    // Buat koneksi ke database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Atur mode error PDO ke Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $category = $_GET['category'];

    // Lakukan kueri SQL untuk mengambil data produk berdasarkan kategori
    $query = "SELECT * FROM products WHERE `category` = :category";
    // Prepare statement
    $stm = $pdo->prepare($query);
    // Bind parameter
    $stm->bindValue(':category', $category);
    // Execute the statement
    $stm->execute();

    // Buat HTML untuk menampilkan semua produk
    $html = '';
    while ($fetch = $stm->fetch()) {
        $html .= '
        <div class="product-container">
            <div class="product">
                <img src="' . $fetch['image'] . '" alt="' . $fetch['name'] . '" />
                <h3>' . $fetch['name'] . '</h3>
                <p>' . $fetch['description'] . '</p>
                <p class="price">Rp' . number_format($fetch['price'], 0, ',', '.') . '</p>';
        if (isset($_SESSION['username'])) {
            $html .= '
                <div class="add-to-cart">
                    <form action="add-to-shopping-cart.php" method="POST">
                        <input type="hidden" name="product_id" value="' . $fetch['id'] . '">
                        <input type="hidden" name="product_name" value="' . $fetch['name'] . '">
                        <input type="hidden" name="product_price" value="' . $fetch['price'] . '">
                        <input type="hidden" name="product_img" value="' . $fetch['image'] . '">
                        <button type="submit" name="add_to_cart"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                    </form>
                </div>';
        } else {
            $html .= '
                <div class="add-to-cart">
                    <a href="my-account.php" class="add-cart"><i class="fas fa-cart-plus"></i> Login to add to cart</a>
                </div>';
        }

        $html .= '
            </div>
        </div>
        ';
    }
    echo $html;
} catch (PDOException $e) {
    // Tangani kesalahan jika gagal terkoneksi ke database atau gagal mengeksekusi kueri SQL
    echo "Error: " . $e->getMessage();
}
?>