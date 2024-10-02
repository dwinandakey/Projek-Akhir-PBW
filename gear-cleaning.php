<?php
session_start();

// Include database configuration and navbar
include 'config/database.php';
include 'assets/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - Gear Cleaning</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="styles/gear-cleaning.css">
</head>

<body>
    <div class="container">
        <h2>Gear Cleaning</h2>
        <form method="post" action="gear-cleaning-action.php" enctype="multipart/form-data">
            <label for="item_description">Item Description:</label>
            <select name="item_description" id="item_description" required onchange="updateTotalAmount()">
                <option value="" data-price="0" disabled selected>Please select an item!</option>
                <option value="Daypack" data-price="25000">Daypack - 25k</option>
                <option value="Carrier 45 L" data-price="30000">Carrier 45 L - 30k</option>
                <option value="Carrier 65 L" data-price="40000">Carrier 65 L - 40k</option>
                <option value="Carrier 80 L" data-price="50000">Carrier 80 L - 50k</option>
                <option value="Cover Bag" data-price="20000">Cover Bag - 20k</option>
                <option value="Matras" data-price="10000">Matras - 10k</option>
                <option value="Sleeping Bag Polar/Dacron" data-price="25000">Sleeping Bag Polar/Dacron - 25k</option>
                <option value="Sleeping Bag Mummy/Bulu Angsa" data-price="35000">Sleeping Bag Mummy/Bulu Angsa - 35k
                </option>
                <option value="Hammock" data-price="20000">Hammock - 20k</option>
                <option value="Flysheet 3x3/4x4" data-price="20000">Flysheet 3x3/4x4 - 20k</option>
                <option value="Sepatu" data-price="35000">Sepatu - 35k</option>
                <option value="Jaket Polar" data-price="95000">Jaket Polar - 95k</option>
                <option value="Jaket Bulu Angsa" data-price="45000">Jaket Bulu Angsa - 45k</option>
                <option value="Tenda 2/3 Orang Single Layer" data-price="40000">Tenda 2/3 Orang Single Layer - 40k
                </option>
                <option value="Tenda 4/5 Orang Single Layer" data-price="50000">Tenda 4/5 Orang Single Layer - 50k
                </option>
                <option value="Tenda 2/3 Orang Double Layer" data-price="60000">Tenda 2/3 Orang Double Layer - 60k
                </option>
                <option value="Tenda 4/5 Orang Double Layer" data-price="70000">Tenda 4/5 Orang Double Layer - 70k
                </option>
                <option value="Tenda 7-8 Orang Single Layer" data-price="75000">Tenda 7-8 Orang Single Layer - 75k
                </option>
                <option value="Tenda 7-8 Orang Double Layer" data-price="85000">Tenda 7-8 Orang Double Layer - 85k
                </option>
            </select>
            <label for="cleaning_date">Preferred Cleaning Date:</label>
            <?php
            $date = date('Y-m-d');
            $nextDay = date('Y-m-d', strtotime($date . '+1 day'));
            $maxDay = date('Y-m-d', strtotime($date . '+30 day'));
            ?>
            <input type="date" name="cleaning_date" min="<?= $nextDay ?>" max="<?= $maxDay ?>" required>
            <label for="total_amount">Total Amount (Rp.):</label>
            <input type="number" id="total_amount" name="total_amount" readonly required>
            <div class="field text-gambar">
                <p>Upload Item Image:</p>
                <div type="button" id="select-image-item" class="select-image">Pilih Gambar</div>
                <input type="file" id="file-item" accept="image/*" name="image" required style="opacity: 0;">
            </div>
            <div class="add-menu-container">
                <div class="img-area-item" data-img="">
                    <i class="fas fa-upload icon"></i>
                    <h3>Upload Image</h3>
                    <p>Ukuran gambar harus kurang dari <span>2MB</span></p>
                </div>
            </div>
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
                <input type="file" id="file" accept="image/*" name="bukti_pembayaran" required style="opacity: 0;">
            </div>
            <div class="add-menu-container">
                <div class="img-area" data-img="">
                    <i class="fas fa-upload icon"></i>
                    <h3>Upload Image</h3>
                    <p>Ukuran gambar harus kurang dari <span>2MB</span></p>
                </div>
            </div>
            <?php if (!isset($_SESSION['username'])): ?>
                <button type="button" onclick="window.location.href='my-account.php'">Login to continue</button>
            <?php else: ?>
                <button type="submit">Submit</button>
            <?php endif; ?>
        </form>
    </div>
    <script src="js/gear-cleaning.js"></script>
</body>

</html>