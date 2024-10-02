<?php
session_start();
include 'config/database.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input data
    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan password harus diisi.'); window.location.href='my-account.php';</script>";
        exit();
    }

    // Prepare SQL statement to avoid SQL injection
    $sql = "SELECT id, nama, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Set cookie
            $expiry_time = time() + 60 * 60 * 24 * 30; // 30 hari
            setcookie('user_id', $user['id'], $expiry_time, '/', '', true, true); // Secure dan HttpOnly

            // Check if the user is admin
            $_SESSION['is_admin'] = ($user['role'] == 'admin');

            echo "<script>alert('Login berhasil'); window.location.href='index.php';</script>";
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Username atau password salah.'); window.location.href='my-account.php';</script>";
        }
    } else {
        // Username not found
        echo "<script>alert('Username atau password salah.'); window.location.href='my-account.php';</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.location.href='my-account.php';</script>";
}
?>