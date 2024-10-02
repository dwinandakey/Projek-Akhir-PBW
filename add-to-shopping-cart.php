<?php
include 'config/database.php';
session_start();

// Aktifkan pelaporan kesalahan untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    $_SESSION['message'] = 'Please login first to add products to the cart';
    $_SESSION['message_type'] = "error";
    header('Location: main-account.php');
    exit();
}

if (isset($_POST['add_to_cart'])) {
    if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
        $p_id = intval($_POST['product_id']);
        $p_quantity = 1; // Default quantity set to 1

        // Get user username from session
        $username = $_SESSION['username'];
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_row = $result->fetch_assoc();
            $user_id = $user_row['id'];

            // Check if the product is already in the cart using prepared statements
            $stmt = $conn->prepare("SELECT id FROM cart WHERE product_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $p_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['message'] = 'Product already added to cart';
                $_SESSION['message_type'] = "error";
            } else {
                // Get the category of the product
                $stmt = $conn->prepare("SELECT category FROM products WHERE id = ?");
                $stmt->bind_param("i", $p_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $product_row = $result->fetch_assoc();
                    $category = $product_row['category'];

                    // Insert the product details into the cart table including category
                    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, category) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiis", $user_id, $p_id, $p_quantity, $category);

                    if ($stmt->execute()) {
                        $_SESSION['message'] = 'Product added to cart successfully';
                        $_SESSION['message_type'] = "success";
                    } else {
                        $_SESSION['message'] = 'Error adding product to cart: ' . $stmt->error;
                        $_SESSION['message_type'] = "error";
                    }
                } else {
                    $_SESSION['message'] = 'Product category not found';
                    $_SESSION['message_type'] = "error";
                }
            }

            $stmt->close();
        } else {
            $_SESSION['message'] = 'User not found';
            $_SESSION['message_type'] = "error";
        }

        header('Location: products.php');
        exit();
    } else {
        $_SESSION['message'] = 'Invalid product ID';
        $_SESSION['message_type'] = "error";
        header('Location: products.php');
        exit();
    }
}
?>