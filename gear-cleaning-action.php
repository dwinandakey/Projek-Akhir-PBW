<?php
include 'config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $item_description = htmlspecialchars(strip_tags(trim($_POST['item_description'])));
    $cleaning_date = $_POST['cleaning_date'];
    $total_amount = $_POST['total_amount'];
    $payment_method = $_POST['payment_method'];
    $image = $_FILES['image'];
    $payment_proof = $_FILES['bukti_pembayaran'];

    if (empty($item_description) || empty($cleaning_date) || empty($total_amount) || empty($payment_method) || empty($image['name']) || empty($payment_proof['name'])) {
        echo "<script>alert('Please fill in all fields!'); window.location.href='gear-cleaning.php';</script>";
        exit;
    }

    // Handle the file upload for the item image
    $image = $_FILES['image'];
    $image_target_dir = "upload/cleaning/";
    $image_target_file = $image_target_dir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($image_target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File is not an image.'); window.location.href='gear-cleaning.php';</script>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($image_target_file)) {
        echo "<script>alert('Sorry, file already exists.'); window.location.href='gear-cleaning.php';</script>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed'); window.location.href='gear-cleaning.php';</script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.'); window.location.href='gear-cleaning.php';</script>";
    } else {
        if (move_uploaded_file($image["tmp_name"], $image_target_file)) {
            // Handle the file upload for the payment proof
            $payment_proof = $_FILES['bukti_pembayaran'];
            $proof_target_dir = "payment_proofs/cleaning/";
            $proof_target_file = $proof_target_dir . basename($payment_proof["name"]);
            $proofFileType = strtolower(pathinfo($proof_target_file, PATHINFO_EXTENSION));
            $uploadProofOk = 1;

            // Check if image file is an actual image or fake image
            $check = getimagesize($payment_proof["tmp_name"]);
            if ($check !== false) {
                $uploadProofOk = 1;
            } else {
                echo "<script>alert('Payment proof file is not an image.'); window.location.href='gear-cleaning.php';</script>";
                $uploadProofOk = 0;
            }

            // Check if file already exists
            if (file_exists($proof_target_file)) {
                echo "<script>alert('Sorry, payment proof file already exists.'); window.location.href='gear-cleaning.php';</script>";
                $uploadProofOk = 0;
            }

            // Allow certain file formats
            if ($proofFileType != "jpg" && $proofFileType != "png" && $proofFileType != "jpeg" && $proofFileType != "gif") {
                echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed for payment proof.'); window.location.href='gear-cleaning.php';</script>";
                $uploadProofOk = 0;
            }

            // Check if $uploadProofOk is set to 0 by an error
            if ($uploadProofOk == 0) {
                echo "<script>alert('Sorry, your payment proof file was not uploaded.'); window.location.href='gear-cleaning.php';</script>";
            } else {
                if (move_uploaded_file($payment_proof["tmp_name"], $proof_target_file)) {
                    // Insert cleaning request into the database
                    $stmt = $conn->prepare("INSERT INTO gear_cleaning (user_id, item_description, cleaning_date, total_amount, payment_method, image, payment_proof, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
                    $stmt->bind_param("issssss", $user_id, $item_description, $cleaning_date, $total_amount, $payment_method, $image_target_file, $proof_target_file);

                    if ($stmt->execute()) {
                        echo "<script>alert('Gear cleaning request submitted successfully.'); window.location.href='gear-cleaning.php';</script>";
                    } else {
                        echo "<script>alert('Error: ' . $stmt->error); window.location.href='gear-cleaning.php';</script>";
                    }
                    $stmt->close();
                } else {
                    echo "<script>alert('Sorry, there was an error uploading your payment proof file.'); window.location.href='gear-cleaning.php';</script>";
                }
            }
        } else {
            echo "<script>alert('Sorry, there was an error uploading your item image file.'); window.location.href='gear-cleaning.php';</script>";
        }
    }
}
?>