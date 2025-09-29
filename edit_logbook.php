<?php
include 'db.php';
session_start();

if (!isset($_SESSION['role']) || !isset($_SESSION['nama'])) {
    die("Akses ditolak!");
}

$id = $_GET['id'];

// Ambil data logbook berdasarkan ID
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM logbook WHERE id='$id'"));

// Batasi akses jika role adalah 'petugas' dan nama logbook tidak sesuai dengan nama di session
if ($_SESSION['role'] == 'petugas' && $data['nama'] != $_SESSION['nama']) {
    die("Akses ditolak! Anda hanya dapat mengedit data milik Anda sendiri.");
}

// Tangani POST (jika form disubmit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $unit = $_POST['unit'];
    $intervensi = $_POST['intervensi'];

    // Petugas tidak boleh mengubah nama
    if ($_SESSION['role'] == 'petugas') {
        $nama = $_SESSION['nama']; // paksa nama dari session
    }

    mysqli_query($conn, "UPDATE logbook SET nama='$nama', unit='$unit', intervensi='$intervensi' WHERE id='$id'");
    header("Location: dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Edit Logbook</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
  <h2>Edit Data Logbook</h2>
  <form method="post">
    <label>Nama Petugas</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" <?= ($_SESSION['role'] == 'petugas') ? 'readonly' : 'required' ?>>


    <label>Unit Kerja</label>
    <input type="text" name="unit" value="<?= htmlspecialchars($data['unit']) ?>" required>

    <label>Jenis Intervensi / Tindakan</label>
    <textarea name="intervensi" required><?= htmlspecialchars($data['intervensi']) ?></textarea>

    <div class="form-actions">
      <button type="submit" class="btn-primary">💾 Simpan Perubahan</button>
      <a href="dashboard.php" class="btn-secondary">← Kembali</a>
    </div>
  </form>
</div>
</body>
</html>
