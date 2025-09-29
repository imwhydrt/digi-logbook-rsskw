<?php
include 'auth.php';
include 'db.php';

if (!in_array($_SESSION['role'], ['admin', 'supervisor', 'petugas'])) {
    echo "Akses ditolak!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Export PDF Logbook</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Export PDF Logbook Keperawatan</h2>
    <form method="GET" action="export_pdf.php" class="mt-4">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" required>
            </div>
        </div>

        <!-- Optional filter: nama dan unit -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cari_nama" class="form-label">Cari Nama Petugas</label>
                <input type="text" name="cari_nama" class="form-control">
            </div>
            <?php if ($_SESSION['role'] == 'admin'): ?>
            <div class="col-md-4">
                <label for="cari_unit" class="form-label">Cari Unit</label>
                <input type="text" name="cari_unit" class="form-control">
            </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Export PDF</button>
    </form>
</body>
</html>
