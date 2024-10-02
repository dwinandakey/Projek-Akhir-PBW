<?php
include './config/database.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : ""; // Periksa apakah kunci 'role' ada dalam $_SESSION
    $nama = $_SESSION['nama'];
} else {
    $user_id = "";
    $username = "";
    $role = "";
    $nama = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="./styles/navbar.css">
    <link rel="stylesheet" href="./styles/index.css">
</head>

<body>
    <header>
        <a href="index.php">
            <div class="logo-container">
                <img src="./images/logo.png" alt="NatureGear Logo">
                <h1>
                    <span class="naturegear1">Nature</span>
                    <span class="naturegear2">Gear</span>
                </h1>
            </div>
        </a>
        <nav>
            <ul class="bigscreen">
                <li><a href="index.php">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Services <i class="fa fa-caret-down"></i></a>
                    <div class="dropdown-content">
                        <a href="gear-cleaning.php">Gear Cleaning</a>
                        <a href="equipment-rental.php">Equipment Rental</a>
                    </div>
                </li>
                <li><a href="products.php">Products</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">About <i class="fa fa-caret-down"></i></a>
                    <div class="dropdown-content">
                        <a href="about-us.php">About Us</a>
                        <a href="review.php">Reviews</a>
                    </div>
                </li>
                <li><a href="contact-us.php">Contact Us</a></li>
                <li class="cart dropdown">
                    <a href="#" class="dropbtn">
                        <img src="./images/cart.png" alt="Shopping Cart">
                        <i class="fa fa-caret-down"></i>
                        <?php
                        if (isset($_SESSION['username'])) {
                            // Get user ID
                            $username = $_SESSION['username'];
                            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                            $stmt->bind_param("s", $username);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $user_row = $result->fetch_assoc();
                            $user_id = $user_row['id'];

                            // Get count of items in the cart for the user
                            $select_rows = mysqli_query($conn, "SELECT COUNT(*) AS count FROM cart WHERE user_id = $user_id") or die("query failed");
                            $row_count = mysqli_fetch_assoc($select_rows)['count'];

                            // Display the count next to the cart icon
                            echo "<span>$row_count</span>";
                        }
                        ?>
                    </a>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['username'])) { ?>
                            <a href="cart.php">View Cart</a>
                        <?php } else { ?>
                            <a href="my-account.php">Login to view</a>
                        <?php } ?>
                    </div>
                </li>

                <!-- <li class="search"><a href="search.php"><img src="./images/search.png" alt="Search"></a></li> -->
                <li class="my-account dropdown">
                    <?php if (isset($_SESSION['username'])) { ?>
                        <a href="#" class="dropbtn">
                            <img src="./images/user-check.png" alt="User Profile">
                            <?php echo $_SESSION['username']; ?>
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="user-profile.php">My Profile</a>
                            <a href="orders.php">My Orders</a>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
                                <a class="dropdown-item" href="admin-actions.php">Admin Actions</a><?php } ?>
                            <a href="logout.php">Logout</a>
                        </div>
                    <?php } else { ?>
                        <a href="my-account.php"><img src="./images/user-circle.png" alt="User Profile">Login</a>
                    <?php } ?>
                </li>
            </ul>
        </nav>
        <div class="smallscreen" id="smallscreen">
            <li class="cart dropdown">
                <a href="#" class="dropbtn">
                    <img src="./images/cart.png" alt="Shopping Cart">
                    <i class="fa fa-caret-down"></i>
                    <?php
                    if (isset($_SESSION['username'])) {
                        // Get user ID
                        $username = $_SESSION['username'];
                        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user_row = $result->fetch_assoc();
                        $user_id = $user_row['id'];

                        // Get count of items in the cart for the user
                        $select_rows = mysqli_query($conn, "SELECT COUNT(*) AS count FROM cart WHERE user_id = $user_id") or die("query failed");
                        $row_count = mysqli_fetch_assoc($select_rows)['count'];

                        // Display the count next to the cart icon
                        echo "<span>$row_count</span>";
                    }
                    ?>
                </a>
                <div class="dropdown-content">
                    <?php if (isset($_SESSION['username'])) { ?>
                        <a href="cart.php">View Cart</a>
                    <?php } else { ?>
                        <a href="my-account.php">Login to view</a>
                    <?php } ?>
                </div>
            </li>

            <li class="my-account dropdown">
                <?php if (isset($_SESSION['username'])) { ?>
                    <a href="#" class="dropbtn">
                        <img src="./images/user-check.png" alt="User Profile">
                        <?php echo $_SESSION['username']; ?>
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <div class="dropdown-content">
                            <a href="user-profile.php">My Profile</a>
                            <a href="orders.php">My Orders</a>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) { ?>
                                <a class="dropdown-item" href="admin-actions.php">Admin Actions</a><?php } ?>
                            <a href="logout.php">Logout</a>
                    </div>
                <?php } else { ?>
                    <div class="content">
                        <a href="my-account.php"><img src="./images/user-circle.png" alt="User Profile">Login</a>
                    </div>
                <?php } ?>
            </li>
            <div class="menubutton" id="menubutton">
                <i class="ri-menu-line menu-icon"></i>
            </div>
            <div class="listmenu smallscreen close" id="menulist">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="gear-cleaning.php">Gear Cleaning</a></li>
                    <li><a href="equipment-rental.php">Equipment Rental</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="about-us.php">About Us</a></li>
                    <li><a href="review.php">Reviews</a></li>
                    <li><a href="contact-us.php">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </header>
</body>

</html>
<script src="./js/navbar.js"></script>