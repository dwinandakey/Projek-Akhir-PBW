<?php
include 'config/database.php';
include 'assets/navbar.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: my-account.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_row = $result->fetch_assoc();
$user_id = $user_row['id'];
$email = $user_row['email'];
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - Checkout</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="styles/checkout-shopping.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body>
    <main class="container">
        <h1 class="heading">Checkout</h1>
        <section class="checkout-shopping-items">
            <h2 class="section-heading">Order Summary</h2>
            <?php
            $select_cart = $conn->query("SELECT c.id, c.quantity, p.name, p.price, p.image 
                                         FROM cart c 
                                         JOIN products p ON c.product_id = p.id 
                                         WHERE c.user_id = $user_id");
            $total = 0;
            if ($select_cart->num_rows > 0) {
                while ($fetch_cart = $select_cart->fetch_assoc()) {
                    ?>
                    <div class="checkout-shopping-item-box">
                        <div class="checkout-shopping-card">
                            <div class="checkout-shopping-image">
                                <img src="<?php echo $fetch_cart['image']; ?>" alt="product">
                            </div>
                            <div class="detail">
                                <h4 class="product-name"><?php echo $fetch_cart['name']; ?></h4>
                                <p class="price">Rp<?php echo number_format($fetch_cart['price'], 0, ',', '.'); ?></p>
                                <p class="quantity">Quantity: <?php echo $fetch_cart['quantity']; ?></p>
                                <p class="total-price">Total:
                                    Rp<?php echo number_format($fetch_cart['price'] * $fetch_cart['quantity'], 0, ',', '.'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                    $total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                }
            } else {
                echo '<div class="checkout-shopping-item-box" style="text-align:center; font-weight:bold;">Your cart is empty!</div>';
            }
            ?>
            <div class="total-amount">
                <h3>Total Amount: Rp<?php echo number_format($total, 0, ',', '.'); ?></h3>
            </div>
        </section>

        <section class="payment-details-container">
            <h2 class="section-heading">Lakukan Pembayaran</h2>
            <div class="text-bayar">
                <p>
                    Lakukan pembayaran sebesar $<?php echo number_format($total, 0, ',', '.'); ?> ke
                    <br> <br> Mandiri: 1330025102476
                    <br> Gopay/ShopeePay/Dana : 082213104552
                    <br> <br> atas nama NatureGear
                </p>
            </div>
            <form action="checkout-shopping-action.php" method="post" enctype="multipart/form-data" class="input_box">
                <div class="payment_method-section">
                    <label for="payment_method">Choose Payment Method:</label>
                    <select name="payment_method" id="payment_method" required>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="GoPay">GoPay</option>
                        <option value="ShopeePay">ShopeePay</option>
                        <option value="Dana">Dana</option>
                    </select>
                </div>
                <div class="field text-gambar">
                    <p>Unggah Pembayaran</p>
                    <div type="button" id="select-image" class="select-image">Pilih Gambar</div>
                </div>
                <div class="add-menu-container">
                    <input type="file" id="file" accept="image/*" name="bukti_pembayaran" required style="opacity: 0;">
                    <div class="img-area" data-img="">
                        <i class="fas fa-upload icon"></i>
                        <h3>Upload Image</h3>
                        <p>Ukuran gambar harus kurang dari <span>2MB</span></p>
                    </div>
                </div>
                <div class="checkout-shopping-action">
                    <button type="submit" name="submit" class="checkout-button">Place Order</button>
                </div>
            </form>
        </section>
    </main>
    <?php include 'assets/footer.php'; ?>
</body>

</html>
<script src="js/checkout-shopping.js"></script>
