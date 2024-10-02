<?php
session_start();
include ('config/database.php');
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $nama = $_SESSION['nama'];
    $username = $_SESSION['username'];
} else {
    $user_id = "";
    $nama = "NatureGear";
    $username = "NatureGear";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - Reviews</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="styles/review.css">
</head>

<body onload="showTime()">
    <!-- Header Section -->
    <?php include ('assets/navbar.php'); ?>
    <!-- Header Section End -->

    <!-- Reviews Section -->
    <h1 class="heading top-top">Kata <span>Mereka</span></h1>
    <p class="heading-description">
        Kami selalu mendengarkan kritik/saran dari pelanggan-pelanggan kami
    </p>

    <div id="add-review">
        <div class="add-review-box">
            <div class="box-top">
                <div class="profile">
                    <div class="profile-img">
                        <img src="images/logo.png" alt="Profile Image">
                    </div>
                    <div class="name-user">
                    <strong><?= empty($user_id) ? 'NatureGear' : htmlspecialchars($nama); ?></strong>
                        <span><?= empty($user_id) ? '@NatureGear' : '@' . htmlspecialchars($username); ?></span>
                    </div>
                </div>
                <div class="time">
                    <strong id="tanggal">Tanggal</strong><br>
                    <span id="jam">Jam</span>
                </div>
            </div>
            <form class="review-form" action="review_action.php" method="post" onsubmit="return validateForm();">
                <input type="text" name="komentar" id="add-comment" placeholder="Ceritakan pengalamanmu di sini"
                    required>
                <?php if (empty($user_id)): ?>
                    <p class="noLogIn">Silakan Sign In untuk menceritakan pengalamanmu</p>
                <?php else: ?>
                    <button name="submit">Tambahkan</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <section class="reviews" id="reviews">
        <?php
        $stm = $conn->query("SELECT * FROM reviews ORDER BY id DESC");
        while ($fetch = $stm->fetch_assoc()) {
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
    </section>
    <!-- Reviews Section End -->

    <?php include ('assets/footer.php'); ?>

</body>

</html>
<script src="js/review.js"></script>