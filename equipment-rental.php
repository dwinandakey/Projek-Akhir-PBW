<?php
include 'config/database.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - Equipment Rental</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="styles/equipment-rental.css">
</head>

<body>
    <!-- Navbar Section -->
    <?php include ('assets/navbar.php'); ?>
    <!-- Navbar Section End -->

    <div class="container">
        <h2>Rent Equipment</h2>
        <form action="equipment-rental-action.php" method="POST" enctype="multipart/form-data"
            onsubmit="return validasiTanggal()">
            <div class="form-group">
                <label for="equipment">Select Equipment:</label>
                <select name="equipment_id" id="equipment_id" class="form-control" required>
                    <?php
                    $result = $conn->query("SELECT id, name, price FROM products WHERE availability = 'Available'");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}' data-price='{$row['price']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rental_start">Rental Start Date:</label>
                <?php
                $date = date('Y-m-d');
                $nextDay = date('Y-m-d', strtotime($date . ' +1 day'));
                $maxDay = date('Y-m-d', strtotime($date . ' +30 day'));
                ?>
                <input type="date" name="rental_start" id="rental_start" min="<?= $nextDay ?>" max="<?= $maxDay ?>"
                    class="form-control" required>
            </div>
            <div class="form-group">
                <label for="rental_end">Rental End Date:</label>
                <input type="date" name="rental_end" id="rental_end" min="<?= $nextDay ?>" max="<?= $maxDay ?>"
                    class="form-control" required>
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount (Rp.):</label>
                <input type="text" name="total_amount" id="total_amount" class="form-control" readonly>
            </div>
            <div class="form-group">
                <h2 class="section-heading">Lakukan Pembayaran</h2>
                <div class="text-bayar">
                    <p>
                        Lakukan pembayaran sebesar Rp<span id="payment_amount"></span> ke
                        <br> <br> Mandiri: 1330025102476
                        <br> Gopay/ShopeePay/Dana : 082213104552
                        <br> <br> atas nama NatureGear
                    </p>
                </div>
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
            </div>
            <?php if (!isset($_SESSION['username'])): ?>
                <button type="button" onclick="window.location.href='my-account.php'">Login to continue</button>
            <?php else: ?>
                <button type="submit">Submit</button>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>
<script src="js/equipment-rental.js"></script>