<?php
session_start();
include ('config/database.php');

try {
    // Buat koneksi ke database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Atur mode error PDO ke Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lakukan kueri SQL untuk mengambil data produk
    $query = "SELECT id, name, description, price, image FROM products";
    $stm = $pdo->query($query);

    // Buat HTML untuk menampilkan produk
    $html = '';
    while ($fetch = $stm->fetch()) {
        $html .= '
        <div class="product-container">
            <div class="product">
                <img src="' . htmlspecialchars($fetch['image']) . '" alt="' . htmlspecialchars($fetch['name']) . '" />
                <h3>' . htmlspecialchars($fetch['name']) . '</h3>
                <p>' . htmlspecialchars($fetch['description']) . '</p>
                <p class="price">Rp' . number_format($fetch['price'], 0, ',', '.') . '</p>';
        if (isset($_SESSION['username'])) {
            $html .= '
                <form action="add-to-shopping-cart.php" method="POST">
                    <input type="hidden" name="product_id" value="' . htmlspecialchars($fetch['id']) . '">
                    <input type="hidden" name="product_name" value="' . htmlspecialchars($fetch['name']) . '">
                    <input type="hidden" name="product_price" value="' . htmlspecialchars($fetch['price']) . '">
                    <input type="hidden" name="product_img" value="' . htmlspecialchars($fetch['image']) . '">
                    <button type="submit" name="add_to_cart" class="add-cart"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                </form>';
        } else {
            $html .= '<a href="my-account.php" class="add-cart"><i class="fas fa-cart-plus"></i> Login to add to cart</a>';
        }

        $html .= '
            </div>
        </div>';
    }

    echo $html;
} catch (PDOException $e) {
    // Tangani kesalahan jika gagal terkoneksi ke database atau gagal mengeksekusi kueri SQL
    echo "Error: " . $e->getMessage();
}
?>