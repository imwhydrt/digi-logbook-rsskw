<?php
include 'db.php';
session_start();

if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'supervisor') {
    die("Akses ditolak!");
}

if (isset($_POST['acc'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE logbook SET status='disetujui' WHERE id='$id'");
} elseif (isset($_POST['tolak'])) {
    $id = $_POST['id'];
    mysqli_query($conn, "UPDATE logbook SET status='ditolak' WHERE id='$id'");
}

header("Location: dashboard.php");
exit();
