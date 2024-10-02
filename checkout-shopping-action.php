<?php
include 'config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: my-account.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_row = $result->fetch_assoc();
$user_id = $user_row['id'];
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if payment method is selected
    if (!isset($_POST['payment_method']) || empty($_POST['payment_method'])) {
        echo "<script>alert('Payment method is not selected.'); window.location.href='checkout-shopping.php';</script>";
        exit();
    }

    // Check if payment proof is provided
    if (!isset($_FILES['bukti_pembayaran']['name']) || empty($_FILES['bukti_pembayaran']['name'])) {
        echo "<script>alert('Bukti Pembayaran kosong'); window.location.href='checkout-shopping.php';</script>";
        exit();
    }

    $payment_method = $_POST['payment_method'];
    $bukti_pembayaran = $_FILES['bukti_pembayaran']['name'];
    $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];

    // Validate payment proof file extension
    $allowed_extensions = array('png', 'jpg', 'jpeg');
    $file_extension = strtolower(pathinfo($bukti_pembayaran, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<script>alert('Silakan upload file gambar untuk bukti pembayaran'); window.location.href='checkout-shopping.php';</script>";
        exit();
    }

    // Calculate total amount
    $select_cart = $conn->query("SELECT c.quantity, p.price 
                                 FROM cart c 
                                 JOIN products p ON c.product_id = p.id 
                                 WHERE c.user_id = $user_id");

    $total_amount = 0;
    while ($fetch_cart = $select_cart->fetch_assoc()) {
        $total_amount += ($fetch_cart['price'] * $fetch_cart['quantity']);
    }

    if ($total_amount > 0) {
        // Ensure upload directory exists
        $upload_directory = "payment_proofs/shopping/";
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0777, true);
        }

        // Save payment proof image
        $target_path = $upload_directory . basename($bukti_pembayaran);

        if (move_uploaded_file($file_tmp, $target_path)) {
            // Insert order into the database
            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, payment_proof, status) VALUES (?, ?, ?, ?, 'Pending')");
            $stmt->bind_param("idss", $user_id, $total_amount, $payment_method, $target_path);
            $stmt->execute();

            // Clear cart after placing order
            $conn->query("DELETE FROM cart WHERE user_id = $user_id");
            $stmt->close();

            header("Location: orders.php");
            exit();
        } else {
            echo "<script>alert('Failed to upload payment proof.'); window.location.href='checkout-shopping.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Your cart is empty!'); window.location.href='checkout-shopping.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request method.'); window.location.href='checkout-shopping.php';</script>";
    exit();
}
?>