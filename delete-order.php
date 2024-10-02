<?php
include 'config/database.php'; // Sesuaikan dengan path ke file database Anda
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: my-account.php");
    exit();
}

if (isset($_REQUEST['id'])) {
    $order_id = $_GET['id']; // Ambil ID pesanan yang akan dihapus

    // Retrieve the payment proof path from the database
    if ($stmt = $conn->prepare("SELECT payment_proof FROM orders WHERE id = ?")) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if ($order) {
            $payment_proof_path = $order['payment_proof'];

            // Delete payment proof file from the server
            if ($payment_proof_path && file_exists($payment_proof_path)) {
                if (!unlink($payment_proof_path)) {
                    echo "<script>alert('Failed to delete payment proof file.'); 
                window.location.href = 'orders.php';</script>";
                    exit();
                }
            }

            // Delete the order from the database
            if ($stmt = $conn->prepare("DELETE FROM orders WHERE id = ?")) {
                $stmt->bind_param("i", $order_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<script>alert('Order deleted successfully.'); 
                    window.location.href = 'orders.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Failed to delete order'); 
                    window.location.href = 'orders.php';</script>";
                }
                $stmt->close();
            } else {
                echo "<script>alert('Failed to prepare delete statement.'); 
                window.location.href = 'orders.php';</script>";
            }
        } else {
            echo "<script>alert('Order not found.'); 
                window.location.href = 'orders.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to prepare select statement.'); 
        window.location.href = 'orders.php';</script>";
    }
} else {
    header("Location: orders.php");
    exit();
}
?>