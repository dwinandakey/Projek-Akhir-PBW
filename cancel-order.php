<?php
include 'config/database.php'; // Sesuaikan dengan path ke file database Anda
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: my-account.php");
    exit();
}

if (isset($_REQUEST['id'])) {
    $order_id = $_GET['id']; // Ambil ID pesanan yang akan dihapus

    // Lakukan proses penghapusan pesanan di sini
    $stmt = $conn->prepare("UPDATE orders SET status = 'Canceled' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    // Periksa apakah penghapusan berhasil
    if ($stmt->affected_rows > 0) {
        // Jika berhasil, arahkan pengguna kembali ke halaman "My Orders" dan tampilkan pesan sukses
        echo "<script>alert('Shopping order canceled successfully.'); 
        window.location.href = 'orders.php';</script>";
        exit();
    } else {
        // Jika gagal, tampilkan pesan kesalahan
        echo "<script>alert('Failed to delete order.');</script>";
    }

    $stmt->close();
} else {
    // Jika tidak ada ID pesanan yang diberikan, arahkan pengguna ke halaman "My Orders"
    header("Location: orders.php");
    exit();
}
?>