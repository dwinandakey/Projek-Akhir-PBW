<?php
include 'config/database.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: my-account.php");
    exit();
}

if (isset($_REQUEST['id'])) {
    $order_id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE equipment_rental SET status = 'Canceled' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Rental order canceled successfully.'); 
        window.location.href = 'orders.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to cancel rental order.');</script>";
    }

    $stmt->close();
} else {
    header("Location: orders.php");
    exit();
}
?>
