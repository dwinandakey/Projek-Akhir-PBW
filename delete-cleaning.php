<?php
include 'config/database.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: my-account.php");
    exit();
}

if (isset($_REQUEST['id'])) {
    $cleaning_id = $_GET['id'];

    // Retrieve the image and payment proof paths
    $stmt = $conn->prepare("SELECT image, payment_proof FROM gear_cleaning WHERE id = ?");
    $stmt->bind_param("i", $cleaning_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cleaning = $result->fetch_assoc();
    $stmt->close();

    if ($cleaning) {
        $image_path = $cleaning['image'];
        $payment_proof_path = $cleaning['payment_proof'];

        // Delete image file
        if ($image_path && file_exists($image_path)) {
            if (!unlink($image_path)) {
                echo "<script>alert('Gagal menghapus file gambar.'); 
            window.location.href = 'orders.php';</script>";
            }
        }

        // Delete payment proof file
        if ($payment_proof_path && file_exists($payment_proof_path)) {
            if (!unlink($payment_proof_path)) {
                echo "<script>alert('Gagal menghapus file bukti pembayaran.'); 
                window.location.href = 'orders.php';</script>";
            }
        }

        // Delete the cleaning order
        $stmt = $conn->prepare("DELETE FROM gear_cleaning WHERE id = ?");
        $stmt->bind_param("i", $cleaning_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Cleaning order deleted successfully.'); 
            window.location.href = 'orders.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to delete cleaning order.');
            window.location.href = 'orders.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Cleaning order not found.');
        window.location.href = 'orders.php';</script>";
    }
} else {
    header("Location: orders.php");
    exit();
}
?>