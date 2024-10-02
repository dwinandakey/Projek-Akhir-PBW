<?php
session_start();

include 'config/database.php';

// Check if the user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized access.'); window.location.href='index.php';</script>";
    exit();
}

// Add product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_product') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $availability = $_POST['availability'];

    if (empty($name) || empty($price) || empty($category)) {
        echo "<script>alert('Name, price, and category are required.'); window.location.href='admin-actions.php';</script>";
        exit();
    }

    $upload_directory = "images/";
    $target_path = $upload_directory . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $sql = "INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $name, $description, $price, $category, $target_path);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Product added successfully.'); window.location.href='admin-actions.php';</script>";
    } else {
        echo "<script>alert('Failed to upload image.'); window.location.href='admin-actions.php';</script>";
    }
}

// Fetch product details for editing
$product = null;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Update Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_product') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $availability = $_POST['availability'];
    $old_image = $_POST['old_image'];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $upload_directory = "images/";
        $target_path = $upload_directory . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
        // Hapus old image
        if (file_exists($old_image)) {
            unlink($old_image);
        }
    } else {
        $target_path = $old_image;
    }

    $sql = "UPDATE products SET name = ?, description = ?, price = ?, category = ?, availability = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsssi", $name, $description, $price, $category, $availability, $target_path, $product_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Product updated successfully.'); window.location.href='admin-actions.php';</script>";
}

// Delete product
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete_product') {
    try {
        $product_id = $_GET['product_id'];

        // Ambil path gambar dari database
        $sql = "SELECT image FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $image_path = $product['image'];

        // Hapus file gambar dari server
        if (file_exists($image_path)) {
            if (!unlink($image_path)) {
                echo "<script>alert('Gagal menghapus file gambar.'); window.location.href='admin-actions.php';</script>";
            }
        }

        // Hapus data produk dari database
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Product deleted successfully.'); window.location.href='admin-actions.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error deleting product: " . $e->getMessage() . "'); window.location.href='admin-actions.php';</script>";
    }

}

// Initialize the search results array
$searchResults = [];
$allProducts = [];

try {
    // Create a connection to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Process search query
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
        $search = $_GET['search'];
        $searchTerm = "%$search%";

        // Prepare the SQL query using PDO
        $sql = "SELECT * FROM products WHERE name LIKE :searchTerm OR category LIKE :searchTerm";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $searchResults = $stmt->fetchAll();
    } else {
        // If no search, fetch all products
        $sql = "SELECT * FROM products";
        $stmt = $pdo->query($sql);
        $allProducts = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'update_order') {
    $order_id = $_GET['order_id'];
    $status = $_GET['status'];

    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();

    echo "
<script>alert('Order status updated successfully.'); window.location.href = 'admin-actions.php';</script>";
}

// Delete order
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete_order') {
    try {
        $order_id = $_GET['order_id'];

        // Ambil path gambar dari database
        $sql = "SELECT payment_proof FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $image_path = $order['payment_proof'];

        // Hapus file gambar dari server
        if (file_exists($image_path)) {
            if (!unlink($image_path)) {
                echo "<script>alert('Gagal menghapus file bukti pembayaran.'); window.location.href='admin-actions.php';</script>";
            }
        }

        // Hapus data pesanan dari database
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Order deleted successfully.'); window.location.href='admin-actions.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error deleting order: " . $e->getMessage() . "'); window.location.href='admin-actions.php';</script>";
    }
}

// Update rental status
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'update_rental') {
    $rental_id = $_GET['rental_id'];
    $status = $_GET['status'];

    $sql = "UPDATE equipment_rental SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $rental_id);
    $stmt->execute();
    $stmt->close();

    echo "
<script>alert('Rental status updated successfully.'); window.location.href = 'admin-actions.php';</script>";
}

// Delete rental
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete_rental') {
    $rental_id = $_GET['rental_id'];

    try {
        // Get the payment proof file path and the equipment id from the rental record
        $sql = "SELECT payment_proof, equipment_id FROM equipment_rental WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $rental_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rental = $result->fetch_assoc();
        $payment_proof_path = $rental['payment_proof'];

        if ($result->num_rows > 0) {
            $equipment_id = $rental['equipment_id'];

            // Delete the payment proof file from the server
            if (file_exists($payment_proof_path)) {
                if (!unlink($payment_proof_path)) {
                    echo "<script>alert('Failed to delete payment proof file.'); window.location.href='admin-actions.php';</script>";
                }
            }

            // Delete the rental record from the database
            $sql = "DELETE FROM equipment_rental WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $rental_id);
            $stmt->execute();

            // Update the product availability to 'Available'
            $sql_update = "UPDATE products SET availability = 'Available' WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("i", $equipment_id);
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('Rental deleted successfully.'); window.location.href='admin-actions.php';</script>";
        } else {
            echo "<script>alert('Rental not found.'); window.location.href='admin-actions.php';</script>";
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "<script>alert('Error deleting rental: " . $e->getMessage() . "'); window.location.href='admin-actions.php';</script>";
    }
}


// Update cleaning status
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'update_cleaning') {
    $cleaning_id = $_GET['cleaning_id'];
    $status = $_GET['status'];

    $sql = "UPDATE gear_cleaning SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $cleaning_id);
    $stmt->execute();
    $stmt->close();

    echo "
<script>alert('Cleaning status updated successfully.'); window.location.href = 'admin-actions.php';</script>";
}

// Delete cleaning
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete_cleaning') {
    $cleaning_id = $_GET['cleaning_id'];
    try {
        $sql = "SELECT image, payment_proof FROM gear_cleaning WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cleaning_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cleaning = $result->fetch_assoc();
        $image_path = $cleaning['image'];
        $payment_proof_path = $cleaning['payment_proof'];
        // Hapus file gambar
        if (file_exists($image_path)) {
            if (!unlink($image_path)) {
                echo "<script>alert('Gagal menghapus file gambar.'); window.location.href = 'admin-actions.php';</script>";
            }
        }

        // Hapus file bukti pembayaran
        if (file_exists($payment_proof_path)) {
            if (!unlink($payment_proof_path)) {
                echo "<script>alert('Gagal menghapus file bukti pembayaran.'); window.location.href = 'admin-actions.php';</script>";
            }
        }
        $sql = "DELETE FROM gear_cleaning WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cleaning_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Gear cleaning deleted successfully.'); window.location.href = 'admin-actions.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error deleting order: " . $e->getMessage() . "'); window.location.href='admin-actions.php';</script>";
    }
}

// Delete review
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete_review') {
    $review_id = $_GET['review_id'];

    $sql = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Review deleted successfully.'); window.location.href='admin-actions.php';</script>";
}

// Process search query for user ID by user name
$user_email = null;
$user_username = null;
$user_address = null;
$user_phone = null;
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_name'])) {
    $user_name = $_GET['user_name'];
    try {
        // Create a connection to the database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL query to retrieve user information by user name
        $sql = "SELECT id, email, username, alamat, telp FROM users WHERE nama LIKE :user_name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user information exists
        if ($result) {
            $user_email = $result['email'];
            $user_username = $result['username'];
            $user_address = $result['alamat'];
            $user_phone = $result['telp'];
        } else {
            $user_email = NULL;
            $user_username = NULL;
            $user_address = NULL;
            $user_phone = NULL;
        }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - Admin Actions</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="styles/admin-actions.css">
</head>

<body>
    <?php include 'assets/navbar.php'; ?>
    <h1>Admin Actions</h1>

    <div class="container">
        <h2>Search User Information</h2>
        <form method="GET" action="admin-actions.php">
            <input type="text" name="user_name" placeholder="Enter User Name">
            <button type="submit">Search</button>
        </form>
        <?php
        if (isset($user_id)) {
            if ($user_email === null || $user_username === null || $user_address === null || $user_phone === null) {
                echo "<p>No user found</p>";
            } else {
                echo "<p>Name: $user_name</p>";
                echo "<p>Email: $user_email</p>";
                echo "<p>Username: $user_username</p>";
                echo "<p>Address: $user_address</p>";
                echo "<p>Phone: $user_phone</p>";
            }
        } else {
            echo "<p>No user found</p>";
        }
        ?>
    </div>

    <div class="container">
        <h2>Update and Delete Product</h2>
        <form method="GET" action="admin-actions.php">
            <input type="text" name="search" placeholder="Search for products by name or category">
            <button type="submit" name="allProducts">Show All</button>
            <button type="submit">Search</button>
        </form>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Availability</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php
            // Ambil jumlah halaman dari parameter URL jika ada, jika tidak, defaultnya adalah halaman pertama
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $pageSize = 10; // Jumlah produk per halaman
            $startIndex = ($currentPage - 1) * $pageSize; // Hitung indeks awal produk untuk halaman ini
            
            // Ambil produk yang akan ditampilkan, sesuai dengan parameter pencarian atau tampilkan semua produk jika tidak ada pencarian
            $productsToDisplay = !empty($searchResults) ? $searchResults : $allProducts;

            // Ambil subset produk untuk halaman ini
            $currentPageProducts = array_slice($productsToDisplay, $startIndex, $pageSize);

            // Tampilkan produk untuk halaman ini
            if (!empty($currentPageProducts)) {
                foreach ($currentPageProducts as $product) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['description']) . "</td>";
                    echo "<td>" . number_format($product['price'], 2, ',', '.') . "</td>";
                    echo "<td>" . htmlspecialchars($product['category']) . "</td>";
                    echo "<td>" . htmlspecialchars($product['availability']) . "</td>";
                    echo "<td>" . "<img src='{$product['image']}' alt='" . htmlspecialchars($product['name']) . "'></td>";
                    echo "<td><a href='admin-action-update.php?action=update_product&product_id={$product['id']}' onclick=\"return confirm('Are you sure you want to update this product?')\">Update</a> | ";
                    echo "<a href='admin-actions.php?action=delete_product&product_id={$product['id']}' onclick=\"return confirm('Are you sure you want to delete this product?')\">Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No products found.</td></tr>";
            }
            ?>
        </table>
        <!-- Tampilkan tombol navigasi halaman jika ada lebih dari satu halaman -->
        <?php
        if (count($productsToDisplay) > $pageSize) {
            $numPages = ceil(count($productsToDisplay) / $pageSize); // Hitung jumlah halaman
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $numPages; $i++) {
                // Tampilkan tombol navigasi untuk setiap halaman
                $isActive = $i == $currentPage ? 'active' : '';
                echo "<a class='$isActive' href='?page=$i'>$i</a>";
            }
            echo "</div>";
        }
        ?>
    </div>

    <div class="container">
        <h2>Add Product</h2>
        <form action="admin-actions.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_product">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea><br>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required><br>
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required><br>
            <label for="availability">Availability:</label>
            <input type="text" id="availability" name="availability" required><br>
            <label for="image">Image:</label>
            <div class="field text-gambar">
                <div type="button" id="select-image" class="select-image">Pilih Gambar</div>
                <input type="file" id="file" accept="image/*" name="image" required style="opacity: 0;">
            </div>
            <div class="add-menu-container">
                <div class="img-area" data-img="">
                    <i class="fas fa-upload icon"></i>
                    <h3>Upload Image</h3>
                    <p>Ukuran gambar harus kurang dari <span>2MB</span></p>
                </div>
            </div>
            <input type="submit" value="Add Product">
        </form>
    </div>

    <div class="container">
        <h2>Update and Delete Shopping Order</h2>
        <table>
            <tr>
                <th>No.</th>
                <th>User Name</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Payment Proof</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT orders.id, users.nama AS user_name, orders.order_date, orders.status, orders.total_amount, orders.payment_method, orders.payment_proof 
        FROM orders 
        INNER JOIN users ON orders.user_id = users.id";
            $result = $conn->query($sql);
            $counter = 1;
            if ($result->num_rows > 0) {
                while ($order = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$counter}</td>";
                    echo "<td>{$order['user_name']}</td>";
                    echo "<td>{$order['order_date']}</td>";
                    echo "<td>{$order['status']}</td>";
                    echo "<td>" . number_format($order['total_amount'], 2, ',', '.') . "</td>";
                    echo "<td>{$order['payment_method']}</td>";
                    echo "<td><img src='{$order['payment_proof']}' alt='Payment Proof'></td>";
                    echo "<td>";
                    echo "<a href='admin-actions.php?action=update_order&order_id={$order['id']}&status=Pending' onclick=\"return confirm('Are you sure you want to set this order as Pending?')\">Pending</a> | ";
                    echo "<a href='admin-actions.php?action=update_order&order_id={$order['id']}&status=In Progress' onclick=\"return confirm('Are you sure you want to set this order as In Progress?')\">In Progress</a> | ";
                    echo "<a href='admin-actions.php?action=update_order&order_id={$order['id']}&status=On Hold' onclick=\"return confirm('Are you sure you want to put this order On Hold?')\">On Hold</a> | ";
                    echo "<a href='admin-actions.php?action=update_order&order_id={$order['id']}&status=Completed' onclick=\"return confirm('Are you sure you want to mark this order as Completed?')\">Completed</a> | ";
                    echo "<a href='admin-actions.php?action=update_order&order_id={$order['id']}&status=Canceled' onclick=\"return confirm('Are you sure you want to cancel this order?')\">Canceled</a> | ";
                    echo "<a href='admin-actions.php?action=delete_order&order_id={$order['id']}' onclick=\"return confirm('Are you sure you want to delete this order?')\">Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='8'>No orders found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="container">
        <h2>Update and Delete Equipment Rental Order</h2>
        <table>
            <tr>
                <th>No.</th>
                <th>User Name</th>
                <th>Equipment Name</th>
                <th>Rental Start</th>
                <th>Rental End</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Payment Proof</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT 
            e.id AS rental_id, 
            u.nama AS user_name, 
            p.name AS equipment_name, 
            e.rental_start, 
            e.rental_end, 
            e.status, 
            e.total_amount, 
            e.payment_method, 
            e.payment_proof 
        FROM 
            equipment_rental e
        INNER JOIN 
            users u ON e.user_id = u.id
        INNER JOIN 
            products p ON e.equipment_id = p.id";
            $result = $conn->query($sql);
            $counter = 1;
            if ($result->num_rows > 0) {
                while ($rental = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$counter}</td>";
                    echo "<td>{$rental['user_name']}</td>";
                    echo "<td>{$rental['equipment_name']}</td>";
                    echo "<td>{$rental['rental_start']}</td>";
                    echo "<td>{$rental['rental_end']}</td>";
                    echo "<td>{$rental['status']}</td>";
                    echo "<td>" . number_format($rental['total_amount'], 2, ',', '.') . "</td>";
                    echo "<td>{$rental['payment_method']}</td>";
                    echo "<td><img src='{$rental['payment_proof']}' alt='Payment Proof'</td>";
                    echo "<td>
                            <a href='admin-actions.php?action=update_rental&rental_id={$rental['rental_id']}&status=Approved' onclick=\"return confirm('Are you sure you want to approve this rental order?')\">Approve</a> | 
                            <a href='admin-actions.php?action=update_rental&rental_id={$rental['rental_id']}&status=Rejected' onclick=\"return confirm('Are you sure you want to reject this rental order?')\">Reject</a> |
                            <a href='admin-actions.php?action=update_rental&rental_id={$rental['rental_id']}&status=Completed' onclick=\"return confirm('Are you sure you want to mark this rental order as Completed?')\">Completed</a> |  
                            <a href='admin-actions.php?action=delete_rental&rental_id={$rental['rental_id']}' onclick=\"return confirm('Are you sure you want to delete this rental order?')\">Delete</a> 
                        </td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='10'>No orders found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="container">
        <h2>Update and Delete Gear Cleaning Order</h2>
        <table>
            <tr>
                <th>No.</th>
                <th>User Name</th>
                <th>Item Description</th>
                <th>Image</th>
                <th>Cleaning Date</th>
                <th>Status</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Payment Proof</th>
                <th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT 
            gc.id AS cleaning_id, 
            u.nama AS user_name, 
            gc.item_description, 
            gc.image, 
            gc.cleaning_date, 
            gc.status, 
            gc.total_amount, 
            gc.payment_method, 
            gc.payment_proof 
        FROM 
            gear_cleaning gc
        INNER JOIN 
            users u ON gc.user_id = u.id";
            $result = $conn->query($sql);
            $counter = 1;
            if ($result->num_rows > 0) {
                while ($cleaning = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$counter}</td>";
                    echo "<td>{$cleaning['user_name']}</td>";
                    echo "<td>{$cleaning['item_description']}</td>";
                    echo "<td><img src='{$cleaning['image']}' alt='Gear Cleaning Image'></td>";
                    echo "<td>{$cleaning['cleaning_date']}</td>";
                    echo "<td>{$cleaning['status']}</td>";
                    echo "<td>" . number_format($cleaning['total_amount'], 2, ',', '.') . "</td>";
                    echo "<td>{$cleaning['payment_method']}</td>";
                    echo "<td><img src='{$cleaning['payment_proof']}' alt='Payment Proof'></td>";
                    echo "<td>
            <a href='admin-actions.php?action=update_cleaning&cleaning_id={$cleaning['cleaning_id']}&status=Approved' onclick=\"return confirm('Are you sure you want to approve this cleaning order?')\">Approve</a> | 
            <a href='admin-actions.php?action=update_cleaning&cleaning_id={$cleaning['cleaning_id']}&status=Rejected' onclick=\"return confirm('Are you sure you want to reject this cleaning order?')\">Reject</a> | 
            <a href='admin-actions.php?action=update_cleaning&cleaning_id={$cleaning['cleaning_id']}&status=Completed' onclick=\"return confirm('Are you sure you want to mark this cleaning order as Completed?')\">Completed</a> |
            <a href='admin-actions.php?action=delete_cleaning&cleaning_id={$cleaning['cleaning_id']}' onclick=\"return confirm('Are you sure you want to delete this cleaning order?')\">Delete</a>
        </td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='10'>No orders found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="container">
        <h2>Delete Reviews</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Komentar</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Action</th>
            </tr>
            <?php
            // Query untuk menghitung jumlah total review
            $sqlCount = "SELECT COUNT(*) AS total FROM reviews";
            $resultCount = $conn->query($sqlCount);
            $rowCount = $resultCount->fetch_assoc();
            $totalReviews = $rowCount['total'];

            // Tentukan jumlah review yang ingin ditampilkan per halaman
            $reviewsPerPage = 5;

            // Hitung jumlah halaman
            $numPages = ceil($totalReviews / $reviewsPerPage);

            // Ambil nomor halaman dari parameter URL jika ada, jika tidak, defaultnya adalah halaman pertama
            $currentPage = isset($_GET['review_page']) ? intval($_GET['review_page']) : 1;

            // Hitung indeks awal review untuk halaman ini
            $startIndex = ($currentPage - 1) * $reviewsPerPage;

            // Query untuk mengambil sejumlah review sesuai dengan halaman yang diminta
            $sqlReviews = "SELECT * FROM reviews LIMIT $startIndex, $reviewsPerPage";
            $resultReviews = $conn->query($sqlReviews);

            if ($resultReviews->num_rows > 0) {
                while ($review = $resultReviews->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$review['nama']}</td>";
                    echo "<td>{$review['username']}</td>";
                    echo "<td>{$review['komentar']}</td>";
                    echo "<td>{$review['tanggal']}</td>";
                    echo "<td>{$review['jam']}</td>";
                    echo "<td><a href='admin-actions.php?action=delete_review&review_id={$review['id']}' onclick=\"return confirm('Are you sure you want to delete this review?')\">Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No reviews found.</td></tr>";
            }
            ?>
        </table>
        <?php
        // Tampilkan tombol navigasi halaman jika ada lebih dari satu halaman
        if ($numPages > 1) {
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $numPages; $i++) {
                // Tampilkan tombol navigasi untuk setiap halaman
                $isActive = $i == $currentPage ? 'active' : '';
                echo "<a class='$isActive' href='?review_page=$i'>$i</a>";
            }
            echo "</div>";
        }
        ?>
    </div>
    <?php include 'assets/footer.php'; ?>
    <script src="js/admin-actions.js"></script>
</body>

</html>