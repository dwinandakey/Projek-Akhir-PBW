<?php
include 'config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $equipment_id = $_POST['equipment_id'];
    $rental_start = $_POST['rental_start'];
    $rental_end = $_POST['rental_end'];
    $total_amount = $_POST['total_amount'];
    $payment_method = $_POST['payment_method'];

    // Handle file upload
    $upload_directory = "payment_proofs/rental/";
    if (!is_dir($upload_directory)) {
        mkdir($upload_directory, 0777, true);
    }
    
    $file_name = basename($_FILES['bukti_pembayaran']['name']);
    $file_tmp_name = $_FILES['bukti_pembayaran']['tmp_name'];
    $file_size = $_FILES['bukti_pembayaran']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $target_file = $upload_directory . uniqid() . '.' . $file_ext;

    if (move_uploaded_file($file_tmp_name, $target_file)) {
        $payment_proof = $target_file;

        // Check availability
        $sql_check = "SELECT availability FROM products WHERE id='$equipment_id' AND availability = 'Available'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            // Insert rental request
            $sql = "INSERT INTO equipment_rental (user_id, equipment_id, rental_start, rental_end, status, total_amount, payment_method, payment_proof) 
                VALUES ('$user_id', '$equipment_id', '$rental_start', '$rental_end', 'Pending', '$total_amount', '$payment_method', '$payment_proof')";

            if (mysqli_query($conn, $sql)) {
                // Update product availability
                $sql_update = "UPDATE products SET availability='Unavailable' WHERE id='$equipment_id'";
                mysqli_query($conn, $sql_update);

                echo "<script>alert('Equipment rental request submitted successfully.'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('The equipment is not available for rental.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Failed to upload payment proof.'); window.history.back();</script>";
    }

    mysqli_close($conn);
}
?>
