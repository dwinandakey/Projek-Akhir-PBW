<?php
session_start();

include 'config/database.php';

// Check if the user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized access.'); window.location.href='index.php';</script>";
    exit();
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NatureGear - Update Admin Actions</title>
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
    <h1>Update Product</h1>
    <div class="container">
        <?php if ($product): ?>
            <form action="admin-actions.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_product">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($product['image']); ?>">

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"
                    required><br>

                <label for="description">Description:</label>
                <textarea id="description"
                    name="description"><?php echo htmlspecialchars($product['description']); ?></textarea><br>

                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price"
                    value="<?php echo htmlspecialchars($product['price']); ?>" required><br>

                <label for="category">Category:</label>
                <input type="text" id="category" name="category"
                    value="<?php echo htmlspecialchars($product['category']); ?>" required><br>

                <label for="availability">Availability:</label>
                <input type="text" id="availability" name="availability"
                    value="<?php echo htmlspecialchars($product['availability']); ?>" required>

                <label for="image">Current Image:</label><br>
                <img src="<?php echo $product['image']; ?>" alt="Current Image" width="100"><br>

                <div class="field text-gambar">
                    <p>Upload Item Image:</p>
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

                <input type="submit" value="Update Product">
            </form>
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>
    </div>
    <?php include 'assets/footer.php'; ?>
    <script src="js/admin-action-update.js"></script>
</body>

</html>