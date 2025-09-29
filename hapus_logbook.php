<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || !isset($_GET['id'])) {
    die("Akses ditolak!");
}

$id = $_GET['id'];
$user_role = $_SESSION['role'];
$user_nama = $_SESSION['nama'];

// Ambil data logbook berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM logbook WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

// Cek apakah data ada
if (!$data) {
    die("Data tidak ditemukan.");
}

// Logika akses:
if ($user_role === 'admin' || $user_role === 'supervisor') {
    // Admin dan Supervisor boleh hapus semua
    mysqli_query($conn, "DELETE FROM logbook WHERE id='$id'");
    header("Location: dashboard.php");
    exit();
} elseif ($user_role === 'petugas') {
    // Petugas hanya boleh hapus data miliknya sendiri
    if ($data['nama'] === $user_nama) {
        mysqli_query($conn, "DELETE FROM logbook WHERE id='$id'");
        header("Location: dashboard.php");
        exit();
    } else {
        die("Akses ditolak! Anda hanya dapat menghapus data milik sendiri.");
    }
} else {
    die("Akses ditolak!");
}
