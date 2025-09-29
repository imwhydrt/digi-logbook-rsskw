<?php
include 'db.php';
session_start();
$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $unit = $_POST['unit'];
    $tanggal = $_POST['tanggal'];
    $intervensi = $_POST['intervensi'];
    $no_rm = $_POST['no_rm'];
    $spo = $_POST['spo'];
    $ttd = $_POST['ttd'];
    $shift = $_POST['shift'];

    $sql = "INSERT INTO logbook (nama, unit, tanggal, intervensi, no_rm, spo, ttd, shift) 
            VALUES ('$nama', '$unit', '$tanggal', '$intervensi', '$no_rm', '$spo', '$ttd', '$shift')";

    if (mysqli_query($conn, $sql)) {
        $success = true;
    } else {
        $error = mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logbook Keperawatan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4faff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
            width: 500px;
        }
        .success {
            color: #0c7c59;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .error {
            color: #c0392b;
            font-size: 16px;
            margin-bottom: 20px;
        }
        a {
            background: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if ($success): ?>
        <div class="success">✅ Data berhasil disimpan!</div>
        <a href="index.php">➜ Kembali ke Form Logbook</a>
    <?php else: ?>
        <div class="error">❌ Terjadi kesalahan: <?= htmlspecialchars($error) ?></div>
        <a href="index.php">← Kembali</a>
    <?php endif; ?>
</div>
</body>
</html>
