<?php
// Memulai sesi
session_start();

// Menghapus semua variabel sesi
$_SESSION = array();

// Menghapus cookie sesi jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_unset();
// Mengakhiri sesi
session_destroy();

// Redirect ke halaman login atau halaman lainnya jika diperlukan
header("Location: my-account.php");
exit;
?>