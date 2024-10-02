<?php
session_start();
include ('config/database.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $nama = $_SESSION['nama'];
    $username = $_SESSION['username'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['komentar']) && !empty($user_id)) {
        $komentar = $_POST['komentar'];
        $tanggal = date('d/m/Y');
        $jam = date('H:i:s');

        $stmt = $conn->prepare("INSERT INTO reviews (user_id, nama, username, komentar, tanggal, jam) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $nama, $username, $komentar, $tanggal, $jam);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: review.php");
            exit();
        } else {
            echo "<script>alert('Terjadi kesalahan saat menambahkan review.');</script>";
        }
    } else {
        echo "<script>alert('Silakan isi komentar atau login terlebih dahulu');</script>";
        echo '<script>window.location.href = "review.php";</script>';
    }
}
?>