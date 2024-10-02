<?php
session_start();
// Include file database.php untuk koneksi ke database
include 'config/database.php';

// Ambil data yang dikirim dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$username = $_POST['username'];
$alamat = $_POST['alamat'];
$telp = $_POST['telp'];
$password = $_POST['password'];

// Validasi data (contoh sederhana)
if (empty($nama) || empty($email) || empty($username) || empty($alamat) || empty($telp) || empty($password)) {
    echo "<script>alert('Semua kolom harus diisi.'); window.location.href='my-account.php';</script>";
    exit();
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Email tidak valid.'); window.location.href='my-account.php';</script>";
    exit();
}

// Hash password sebelum disimpan ke database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Cek apakah username atau email sudah terdaftar
$sql = "SELECT * FROM users WHERE username=? OR email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Username atau email sudah terdaftar.'); window.location.href='my-account.php';</script>";
    $stmt->close();
    $conn->close();
    exit();
}

// Query untuk menyimpan data ke database dengan prepared statement
$sql = "INSERT INTO users (nama, email, username, alamat, telp, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $nama, $email, $username, $alamat, $telp, $hashed_password, $role);

// Jalankan query
if ($stmt->execute()) {
    echo "<script>alert('Pendaftaran berhasil.'); window.location.href='my-account.php';</script>";
} else {
    echo "<script>alert('Terjadi kesalahan saat mendaftarkan akun.'); window.location.href='my-account.php';</script>";
}

// Tutup prepared statement dan koneksi ke database
$stmt->close();
$conn->close();
?>