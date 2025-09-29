<?php include 'auth.php'; ?>
<?php 
$role = $_SESSION['role'];
$namaUser = $_SESSION['nama'];
$unitUser = $_SESSION['unit'];

if (!in_array($role, ['admin', 'supervisor', 'petugas'])) {
    echo "<div style='background:#ffebee; padding:30px; text-align:center; font-family:sans-serif;'>
            <h2 style='color:#c62828;'>🚫 Akses Ditolak!</h2>
            <p style='font-size:16px; color:#444;'>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            <a href='javascript:history.back()' style='display:inline-block; margin-top:20px; background:#c62828; color:#fff; padding:10px 20px; border-radius:5px; text-decoration:none;'>🔙 Kembali</a>
        </div>";
    exit();
}
?>

<?php include 'navbar.php'; ?>
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Digi-Logbook</title>
    <link rel="icon" type="image/png" href="/elogbook/logo_skw.jpg">
  <!-- Favicon untuk semua browser -->
  <link rel="icon" href="logo.png" type="image/png" sizes="32x32" />
  <link rel="shortcut icon" href="logo.png" type="image/png" />
  <link rel="apple-touch-icon" href="logo.png" />
  <link rel="icon" href="elogbook/favicon.ico" type="image/x-icon" />
  <!-- Stylesheet -->
  <link rel="stylesheet" href="style.css" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {

             font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f6f9;
        }
        h2 {
            color: #0d47a1;
            margin-bottom: 10px;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            flex: 1 1 200px;
            padding: 20px;
            background-color: #fff;
            border-left: 5px solid #007bff;
            box-shadow: 0 2px 5px rgba(3, 179, 254, 0.61);
            border-radius: 8px;
        }
        .card h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .card p {
            font-size: 24px;
            font-weight: bold;
            color: #0d47a1;
        }
        canvas {
            max-width: 100%;
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 10px rgba(2, 171, 249, 1);
        }
        .chart-container {
            margin: 20px 0;
        }
        

        .container {
            max-width: 100%;
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 900px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ccc;
        }

        th {
            background-color: #e0f0ff;
        }

        .logout-button {
            float: right;
            color: white;
            background: #007BFF;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-edit, .btn-delete, .btn-acc, .btn-tolak {
            padding: 5px 8px;
            margin: 1px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit { background: #ffc107; color: #000; }
        .btn-delete { background: #dc3545; color: #fff; }
        .btn-acc { background: #28a745; color: white; }
        .btn-tolak { background: #6c757d; color: white; }

        .pagination {
            margin-top: 20px;
            text-align: center;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 6px 12px;
            margin: 8px;
            background: #f1f1f1;
            color: black;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a.active {
            background-color: #007BFF;
            color: white;
        }

        .filter-form-modern {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 8px;
    align-items: flex-end;
    border: 1px solid #ddd;
}

.filter-form-modern .filter-group {
    display: flex;
    flex-direction: column;
    flex: 1 1 200px;
    min-width: 180px;
}

.filter-form-modern label {
    font-weight: 500;
    margin-bottom: 2px;
    color: #333;
}

.filter-form-modern input[type="text"],
.filter-form-modern input[type="date"],
.filter-form-modern select {
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    background-color: #fff;
    transition: border-color 0.3s ease;
}

.filter-form-modern input:focus,
.filter-form-modern select:focus {
    border-color: #007bff;
    outline: none;
}

.filter-form-modern button {
    padding: 10px 16px;
    background-color: #007bff;
    color: white;
    font-weight: 600;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.filter-form-modern button:hover {
    background-color: #0056b3;
}
.btn-download {
    display: inline-block;
    background-color: #28a745;
    color: white;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-download:hover {
    background-color: #135f24ff;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    text-decoration: none;
}
    </style>

</head>

<h2>Dashboard Logbook</h2>

<div class="card-container">
<?php
function getCount($conn, $where = '') {
    $sql = "SELECT COUNT(*) as total FROM logbook $where";
    return mysqli_fetch_assoc(mysqli_query($conn, $sql))['total'];
}

if ($role === 'petugas') {
    $whereUser = "WHERE nama='" . mysqli_real_escape_string($conn, $namaUser) . "'";
    echo "<div class='card'><h3>Total Data</h3><p>" . getCount($conn, $whereUser) . "</p></div>";
    echo "<div class='card'><h3>Disetujui</h3><p>" . getCount($conn, "$whereUser AND status='disetujui'") . "</p></div>";
    echo "<div class='card'><h3>Pending</h3><p>" . getCount($conn, "$whereUser AND status='pending'") . "</p></div>";
    echo "<div class='card'><h3>Ditolak</h3><p>" . getCount($conn, "$whereUser AND status='ditolak'") . "</p></div>";
} elseif ($role === 'supervisor') {
    $whereUnit = "WHERE unit='" . mysqli_real_escape_string($conn, $unitUser) . "'";
    echo "<div class='card'><h3>Belum Diverifikasi</h3><p>" . getCount($conn, "$whereUnit AND status='pending'") . "</p></div>";
    echo "<div class='card'><h3>Disetujui</h3><p>" . getCount($conn, "$whereUnit AND status='disetujui'") . "</p></div>";
    echo "<div class='card'><h3>Ditolak</h3><p>" . getCount($conn, "$whereUnit AND status='ditolak'") . "</p></div>";
    echo "<div class='card'><h3>Total Data</h3><p>" . getCount($conn, $whereUnit) . "</p></div>";
} elseif ($role === 'admin') {
    echo "<div class='card'><h3>Total Logbook</h3><p>" . getCount($conn) . "</p></div>";
    echo "<div class='card'><h3>Disetujui</h3><p>" . getCount($conn, "WHERE status='disetujui'") . "</p></div>";
    echo "<div class='card'><h3>Pending</h3><p>" . getCount($conn, "WHERE status='pending'") . "</p></div>";
    echo "<div class='card'><h3>Ditolak</h3><p>" . getCount($conn, "WHERE status='ditolak'") . "</p></div>";
}
?>
</div>

<!-- Filter Form -->
<form method="GET" class="filter-form-modern">
    <div class="filter-group">
        <label>Nama Petugas</label>
        <input type="text" name="cari_nama" placeholder="Cari nama..." value="<?= htmlspecialchars($_GET['cari_nama'] ?? '') ?>">
    </div>

    <div class="filter-group">
        <label>Dari</label>
        <input type="date" name="tanggal_awal" value="<?= htmlspecialchars($_GET['tanggal_awal'] ?? '') ?>">
    </div>

    <div class="filter-group">
        <label>Sampai</label>
        <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($_GET['tanggal_akhir'] ?? '') ?>">
    </div>

    <?php if ($role === 'admin'): ?>
    <div class="filter-group">
        <label>Unit/Ruangan</label>
        <select name="cari_unit">
            <option value="">-- Pilih Ruangan --</option>
            <?php
                $ruangan = [ 
                    "Ade Irma Suryani Lt.1", "Ade Irma Suryani Lt.2", "Aisyah Lt.1", "Aisyah Lt.2",
                    "Cut Nyak Dien", "Cut Meutia", "Inggit Garnasih", "Kartini", "Nyi Ahmad Dahlan",
                    "NICU", "Fatmawati Lt.1", "Fatmawati Lt.2", "Nyi Ageng Serang Lt.1", "Nyi Ageng Serang Lt.2",
                    "Siti Khadijah", "PICU", "Instalasi Bedah Sentral", "Instalagi Gawat Darurat",
                    "Instalasi Rawat Jalan", "Raden Dewi Sartika", "Rasuna Said", "Wijaya Kusuma" 
                ];
                foreach ($ruangan as $r) {
                    $selected = (isset($_GET['cari_unit']) && htmlspecialchars($_GET['cari_unit']) == $r) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($r) . "\" $selected>" . htmlspecialchars($r) . "</option>";
                }
            ?>
        </select>
    </div>
    <?php endif; ?>
    <div class="filter-group">
        <button type="submit">🔍 Cari</button>
    </div>
</form>

<br>
<!-- Tombol Download PDF -->
<a class="btn-download" href="export_pdf.php?cari_nama=<?= urlencode($_GET['cari_nama'] ?? '') ?>&cari_unit=<?= urlencode($_GET['cari_unit'] ?? '') ?>&tanggal_awal=<?= urlencode($_GET['tanggal_awal'] ?? '') ?>&tanggal_akhir=<?= urlencode($_GET['tanggal_akhir'] ?? '') ?>" target="_blank">
    📄 Download PDF
</a>


<br><br>

<?php if ($role === 'petugas'): ?>
    <a href="index.php" style="display:inline-block; margin-bottom:10px; background:#28a745; color:#fff; padding:8px 16px; border-radius:5px; text-decoration:none;">➕ Tambah Data</a>
<?php endif; ?>


<div class="container">
<table>
<tr>
    <th>No</th><th>Nama</th><th>Unit</th><th>Tanggal</th><th>Intervensi</th><th>No. RM</th><th>SPO</th><th>Shift</th><th>Kepala Ruangan</th><th>Status</th><th>Aksi</th>
</tr>

<?php
$where = "WHERE 1=1";
if ($role === 'supervisor') {
    $unitSupervisor = mysqli_real_escape_string($conn, $unitUser);
    $where .= " AND unit = '$unitSupervisor'";
} elseif ($role === 'petugas') {
    $namaPetugas = mysqli_real_escape_string($conn, $namaUser);
    $where .= " AND nama = '$namaPetugas'";
}

if (!empty($_GET['cari_nama'])) {
    $nama = mysqli_real_escape_string($conn, $_GET['cari_nama']);
    $where .= " AND nama LIKE '%$nama%'";
}

if (!empty($_GET['tanggal_awal']) && !empty($_GET['tanggal_akhir'])) {
    $dari = mysqli_real_escape_string($conn, $_GET['tanggal_awal']);
    $sampai = mysqli_real_escape_string($conn, $_GET['tanggal_akhir']);
    $where .= " AND tanggal BETWEEN '$dari' AND '$sampai'";
}



if (!empty($_GET['cari_unit']) && $role === 'admin') {
    $unit = mysqli_real_escape_string($conn, $_GET['cari_unit']);
    $where .= " AND unit LIKE '%$unit%'";
}

$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $limit;

$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM logbook $where");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalData / $limit);

$query = mysqli_query($conn, "SELECT * FROM logbook $where ORDER BY tanggal DESC LIMIT $start, $limit");
$no = $start + 1;

while ($row = mysqli_fetch_assoc($query)) {
    echo "<tr>
<td>{$no}</td>
<td>{$row['nama']}</td>
<td>{$row['unit']}</td>
<td>{$row['tanggal']}</td>
<td>{$row['intervensi']}</td>
<td>{$row['no_rm']}</td>
<td>{$row['spo']}</td>
<td>{$row['shift']}</td>
<td>{$row['ttd']}</td>
<td>{$row['status']}</td>
<td>";

    // Tombol ACC/Tolak hanya untuk supervisor dan admin
    if ($role === 'supervisor' || $role === 'admin') {
        echo "<form method='POST' action='aksi_logbook.php' style='display:inline-block; margin-right:5px;'>
            <input type='hidden' name='id' value='{$row['id']}'>
            <button type='submit' name='acc' class='btn-acc' title='Verifikasi/ACC'>✔️</button>
            <button type='submit' name='tolak' class='btn-tolak' title='Tolak'>❌</button>
        </form>";
    }

    // Edit dan hapus: admin/supervisor bisa semua, petugas hanya miliknya
    if ($role !== 'petugas' || ($role === 'petugas' && $row['nama'] === $namaUser)) {
        echo "<a href='edit_logbook.php?id={$row['id']}' class='btn-edit' title='Edit'>✏️</a>
              <a href='hapus_logbook.php?id={$row['id']}' class='btn-delete' title='Hapus' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">🗑️</a>";
    }

    echo "</td></tr>";
    $no++;
}
?>
</table>
</div>

<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>&cari_nama=<?= urlencode($_GET['cari_nama'] ?? '') ?>&cari_unit=<?= urlencode($_GET['cari_unit'] ?? '') ?>">⏮ Prev</a>
    <?php endif; ?>

    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a class="<?= ($p == $page) ? 'active' : '' ?>" href="?page=<?= $p ?>&cari_nama=<?= urlencode($_GET['cari_nama'] ?? '') ?>&cari_unit=<?= urlencode($_GET['cari_unit'] ?? '') ?>"><?= $p ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>&cari_nama=<?= urlencode($_GET['cari_nama'] ?? '') ?>&cari_unit=<?= urlencode($_GET['cari_unit'] ?? '') ?>">Next ⏭</a>
    <?php endif; ?>
</div>

</body>
</html>
