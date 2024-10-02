<?php
include 'config/database.php';

if (isset($_POST['btn-update'])) {
    $update_id = $_POST['id_quantity'];
    $update_quantity = $_POST['update_quantity'];

    // Validasi nilai quantity
    if (filter_var($update_quantity, FILTER_VALIDATE_INT) !== false && $update_quantity >= 1) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $update_quantity, $update_id);
        $result = $stmt->execute();

        if ($result) {
            header("Location: cart.php");
            exit();
        } else {
            echo 'Error updating quantity: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        echo 'Quantity value is invalid';
    }
}

if (isset($_GET['remove'])) {
    $remove_id = filter_var($_GET['remove'], FILTER_VALIDATE_INT);

    if ($remove_id !== false && $remove_id >= 1) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("i", $remove_id);
        $result = $stmt->execute();

        if ($result) {
            header("Location: cart.php");
            exit();
        } else {
            echo 'Delete error: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        echo 'Invalid item ID';
    }
}

if (isset($_GET['delete_all'])) {
    $stmt = $conn->prepare("DELETE FROM cart");
    $result = $stmt->execute();
    if ($result) {
        header("Location: cart.php");
        exit();
    } else {
        echo 'Delete all error: ' . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart - NatureGear</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="styles/cart.css">
    <link rel="stylesheet" href="styles/navbar_login.css">
    <link rel="stylesheet" href="styles/footer_login.css">
    <!-- Import Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body>
    <?php include 'assets/navbar.php'; ?>

    <div class="container">
        <section class="shopping-cart">
            <h1>Shopping Cart</h1>
            <table>
                <thead>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total price</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    if (isset($_SESSION['username'])) {
                        $username = $_SESSION['username'];
                        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user_row = $result->fetch_assoc();
                        $user_id = $user_row['id'];

                        $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id");
                        $total = 0;
                        if (mysqli_num_rows($select_cart) > 0) {
                            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                $product_id = $fetch_cart['product_id'];
                                $product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
                                $product_data = mysqli_fetch_assoc($product_query);
                                ?>
                                <tr>
                                    <td><img src="<?php echo $product_data['image']; ?>"></td>
                                    <td><?php echo $product_data['name']; ?></td>
                                    <td>Rp<?php echo number_format($product_data['price'], 0, ',', '.'); ?></td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="id_quantity" value="<?php echo $fetch_cart['id']; ?>">
                                            <input type="number" name="update_quantity" min="1"
                                                value="<?php echo $fetch_cart['quantity']; ?>">
                                            <input type="submit" value="Update" name="btn-update">
                                        </form>
                                    </td>
                                    <td>Rp<?php echo $product_data['price'] * $fetch_cart['quantity']; ?></td>
                                    <td><a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>"
                                            onclick="return confirm('Remove item from cart?')" class="btn-del"><i
                                                class="fas fa-trash cart-remove"></i></a></td>
                                </tr>
                                <?php
                                $total += ($product_data['price'] * $fetch_cart['quantity']);
                            }
                        } else {
                            echo '<tr><td colspan="6" style="font-weight: 800; padding: 50px 0;">Your cart is empty!</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" style="font-weight: 800; padding: 50px 0;">Please login to view your cart!</td></tr>';
                    }
                    ?>
                    <tr>
                        <td><a href="products.php" class="btn-co">Continue Shopping</a></td>
                        <td colspan="3" style="font-weight: 800">Total</td>
                        <td style="font-weight: 800">Rp<?php echo $total ?></td>
                        <td><a href="cart.php?delete_all"
                                onclick="return confirm('Are you sure you want to delete all items?');"
                                class="delete-all">Delete all</a></td>
                    </tr>
                </tbody>
            </table>

            <div class="checkout-shopping">
                <a href="checkout-shopping.php" class="btn <?= ($total > 0) ? '' : 'disabled'; ?>">Checkout</a>
            </div>
        </section>
    </div>

    <?php include 'assets/footer.php'; ?>
</body>

</html>