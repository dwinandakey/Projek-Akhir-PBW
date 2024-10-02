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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - My Orders</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="styles/orders.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
</head>

<body>
    <?php include 'assets/navbar.php'; ?>

    <main class="container">
        <h1 class="heading">
            <ion-icon name="clipboard-outline"></ion-icon> My Orders
        </h1>
        <section class="orders-section">
            <?php
            $shopping_orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC");
            $rental_orders = $conn->query("SELECT * FROM equipment_rental WHERE user_id = $user_id ORDER BY id DESC");
            $cleaning_orders = $conn->query("SELECT * FROM gear_cleaning WHERE user_id = $user_id ORDER BY id DESC");
            if (($shopping_orders->num_rows > 0) || ($rental_orders->num_rows) || ($cleaning_orders->num_rows)) {
                $select_orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC");
                if ($select_orders->num_rows > 0) {
                    while ($fetch_order = $select_orders->fetch_assoc()) {
                        ?>
                        <h2 class="section-heading">Shopping Orders</h2>
                        <div class="order-card">
                            <div class="order-details">
                                <p>Order Date & Time: <strong><?php echo $fetch_order['order_date']; ?></strong></p>
                                <p>Total Amount:
                                    <strong>Rp<?php echo number_format($fetch_order['total_amount'], 2, ',', '.'); ?></strong></p>
                                <p>Payment Method: <strong><?php echo $fetch_order['payment_method']; ?></strong></p>
                                <p>Status: <strong><?php echo $fetch_order['status']; ?></strong></p>
                            </div>
                            <?php if ($fetch_order['status'] === 'Completed' || $fetch_order['status'] === 'Canceled') { ?>
                                <div class="order-action">
                                    <button class="delete-btn"
                                        onclick="confirmDeletion(<?php echo $fetch_order['id']; ?>)">Delete</button>
                                </div>
                            <?php } elseif ($fetch_order['status'] === 'Pending') { ?>
                                <div class="order-status pending">
                                    <p>Your order is pending confirmation. We are currently processing your order. Thank you for your
                                        patience.</p>
                                </div>
                                <div class="order-action">
                                    <button class="cancel-btn"
                                        onclick="confirmCancellation(<?php echo $fetch_order['id']; ?>)">Cancel</button>
                                </div>
                            <?php } elseif ($fetch_order['status'] === 'In Progress') { ?>
                                <div class="order-status in-progress">
                                    <p>Your order is currently in progress. Our team is preparing your order for shipment.</p>
                                </div>
                            <?php } elseif ($fetch_order['status'] === 'On Hold') { ?>
                                <div class="order-status on-hold">
                                    <p>Your order is currently on hold. Please contact customer support for further assistance.</p>
                                </div>
                                <div class="order-action">
                                    <button class="cancel-btn"
                                        onclick="confirmCancellation(<?php echo $fetch_order['id']; ?>)">Cancel</button>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </section>

            <section class="orders-section">
                <?php
                $select_orders = $conn->query("SELECT * FROM gear_cleaning WHERE user_id = $user_id  ORDER BY id DESC");

                if ($select_orders->num_rows > 0) {
                    while ($fetch_order = $select_orders->fetch_assoc()) {
                        ?>
                        <h2 class="section-heading">Gear Cleaning Orders</h2>
                        <div class="order-card">
                            <div class="order-details">
                                <p>Gear Cleaning Item: <strong><?php echo $fetch_order['item_description']; ?></strong></p>
                                <p>Gear Cleaning Order Date: <strong><?php echo $fetch_order['cleaning_date']; ?></strong></p>
                                <p>Total Amount: <strong>Rp<?php echo number_format($fetch_order['total_amount'], 2, ',', '.'); ?></strong></p>
                                <p>Payment Method: <strong><?php echo $fetch_order['payment_method']; ?></strong></p>
                                <p>Status: <strong><?php echo $fetch_order['status']; ?></strong></p>
                            </div>
                            <?php if ($fetch_order['status'] === 'Completed' || $fetch_order['status'] === 'Rejected' || $fetch_order['status'] === 'Canceled') { ?>
                                <div class="order-action">
                                    <button class="delete-btn"
                                        onclick="confirmDeletionCleaning(<?php echo $fetch_order['id']; ?>)">Delete</button>
                                </div>
                            <?php } elseif ($fetch_order['status'] === 'Pending') { ?>
                                <div class="order-status pending">
                                    <p>Your order is pending confirmation. We are currently processing your order. Thank you for your
                                        patience.</p>
                                </div>
                                <div class="order-action">
                                    <button class="cancel-btn"
                                        onclick="confirmCancellationCleaning(<?php echo $fetch_order['id']; ?>)">Cancel</button>
                                </div>
                            <?php } elseif ($fetch_order['status'] === 'Approved') { ?>
                                <div class="order-status in-progress">
                                    <p>Your order is currently in progress.</p>
                                </div>
                            <?php } else { ?>
                                <div class="order-action">
                                    <button class="cancel-btn"
                                        onclick="confirmCancellationCleaning(<?php echo $fetch_order['id']; ?>)">Cancel</button>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </section>

            <section class="orders-section">
                <?php
                $select_orders = $conn->query("SELECT er.*, p.name AS equipment_name FROM equipment_rental er JOIN products p ON er.equipment_id = p.id WHERE user_id = $user_id ORDER BY er.id DESC");

                if ($select_orders->num_rows > 0) {
                    while ($fetch_order = $select_orders->fetch_assoc()) {
                        ?>
                        <h2 class="section-heading">Equipment Rental Orders</h2>
                        <div class="order-card">
                            <div class="order-details">
                                <p>Rental Equipment Item: <strong><?php echo $fetch_order['equipment_name']; ?></strong></p>
                                <p>Rental Order Start Date: <strong><?php echo $fetch_order['rental_start']; ?></strong></p>
                                <p>Rental Order End Date: <strong><?php echo $fetch_order['rental_end']; ?></strong></p>
                                <p>Total Amount: <strong>Rp<?php echo number_format($fetch_order['total_amount'], 2, ',', '.'); ?></strong></p>
                                <p>Payment Method: <strong><?php echo $fetch_order['payment_method']; ?></strong></p>
                                <p>Status: <strong><?php echo $fetch_order['status']; ?></strong></p>
                            </div>
                            <?php if ($fetch_order['status'] === 'Completed' || $fetch_order['status'] === 'Rejected' || $fetch_order['status'] === 'Canceled') { ?>
                                <div class="order-action">
                                    <button class="delete-btn"
                                        onclick="confirmDeletionRental(<?php echo $fetch_order['id']; ?>)">Delete</button>
                                </div>
                            <?php } elseif ($fetch_order['status'] === 'Pending') { ?>
                                <div class="order-status pending">
                                    <p>Your order is pending confirmation. We are currently processing your order. Thank you for your
                                        patience.</p>
                                </div>
                                <div class="order-action">
                                    <button class="cancel-btn"
                                        onclick="confirmCancellationRental(<?php echo $fetch_order['id']; ?>)">Cancel</button>
                                </div>
                            <?php } elseif ($fetch_order['status'] === 'Approved') { ?>
                                <div class="order-status in-progress">
                                    <p>Your order is currently in progress.</p>
                                </div>
                            <?php } else { ?>
                                <div class="order-action">
                                    <button class="cancel-btn"
                                        onclick="confirmCancellationRental(<?php echo $fetch_order['id']; ?>)">Cancel</button>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                    }
                }
            } else {
                echo '<div class="no-orders">You have no orders yet!</div>';
            }
            ?>
        </section>
    </main>

    <?php include 'assets/footer.php'; ?>
</body>

</html>
<script src="js/orders.js"></script>