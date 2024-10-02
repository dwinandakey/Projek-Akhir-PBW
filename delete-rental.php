<?php
include 'config/database.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: my-account.php");
    exit();
}

if (isset($_REQUEST['id'])) {
    $rental_id = $_GET['id'];

    // Retrieve the payment proof path and equipment id
    if ($stmt = $conn->prepare("SELECT payment_proof, equipment_id FROM equipment_rental WHERE id = ?")) {

    }
    $stmt->bind_param("i", $rental_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rental = $result->fetch_assoc();
    $stmt->close();

    if ($rental) {
        $payment_proof_path = $rental['payment_proof'];
        $equipment_id = $rental['equipment_id'];

        // Delete payment proof file
        if ($payment_proof_path && file_exists($payment_proof_path)) {
            if (!unlink($payment_proof_path)) {
                echo "<script>alert('Failed to delete payment proof file.'); 
            window.location.href = 'orders.php';</script>";
            }
        }

        // Delete the rental record
        $stmt = $conn->prepare("DELETE FROM equipment_rental WHERE id = ?");
        $stmt->bind_param("i", $rental_id);
        $stmt->execute();
        $stmt->close();

        // Update the product availability to 'Available'
        $stmt = $conn->prepare("UPDATE products SET availability = 'Available' WHERE id = ?");
        $stmt->bind_param("i", $equipment_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Rental order deleted successfully.'); 
             window.location.href = 'orders.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to delete rental order.'); 
            window.location.href = 'orders.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Rental order not found.'); 
        window.location.href = 'orders.php';</script>";
    }
} else {
    header("Location: orders.php");
    exit();
}
?>