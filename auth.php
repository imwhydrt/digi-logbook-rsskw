<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login dengan memverifikasi session penting
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['username']) ||
    !isset($_SESSION['role']) ||
    !isset($_SESSION['nama'])
) {
    // Redirect ke halaman login jika tidak ada session
    header("Location: login.php");
    exit();
}
?>
