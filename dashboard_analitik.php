<?php
include 'auth.php';
include 'db.php';

// Cek hak akses admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['role'] != 'admin') {
    echo "<p style='color:red;'>Akses ditolak! Halaman ini hanya untuk admin.</p>";
    exit();
}

// Total logbook
$totalLogbook = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM logbook"))['total'];

// Logbook per unit
$ruanganQuery = mysqli_query($conn, "SELECT unit, COUNT(*) as jumlah FROM logbook GROUP BY unit ORDER BY jumlah DESC");
$ruanganLabels = $ruanganCounts = [];
while ($row = mysqli_fetch_assoc($ruanganQuery)) {
    $ruanganLabels[] = $row['unit'];
    $ruanganCounts[] = $row['jumlah'];
}

// Top 10 petugas
$userQuery = mysqli_query($conn, "SELECT nama, COUNT(*) as total FROM logbook GROUP BY nama ORDER BY total DESC");
$userData = [];
while ($row = mysqli_fetch_assoc($userQuery)) {
    $userData[] = $row;
}

// Tren logbook 12 bulan terakhir
$bulanLabels = $bulanCounts = [];
$trenQuery = mysqli_query($conn, "
    SELECT DATE_FORMAT(tanggal, '%Y-%m') AS bulan, COUNT(*) AS jumlah 
    FROM logbook 
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY bulan ORDER BY bulan
");
while ($row = mysqli_fetch_assoc($trenQuery)) {
    $bulanLabels[] = $row['bulan'];
    $bulanCounts[] = $row['jumlah'];
}

// Kunjungan per hari (7 hari terakhir)
$hariLabels = $hariCounts = [];
$kunjunganHari = mysqli_query($conn, "
    SELECT DATE(waktu) as tanggal, COUNT(*) as jumlah 
    FROM log_kunjungan 
    WHERE waktu >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(waktu)
    ORDER BY tanggal
");
while ($row = mysqli_fetch_assoc($kunjunganHari)) {
    $hariLabels[] = $row['tanggal'];
    $hariCounts[] = $row['jumlah'];
}

// Kunjungan per bulan (12 bulan terakhir)
$bulanLabelsKunjungan = $bulanCountsKunjungan = [];
$kunjunganBulan = mysqli_query($conn, "
    SELECT DATE_FORMAT(waktu, '%Y-%m') as bulan, COUNT(*) as jumlah 
    FROM log_kunjungan 
    WHERE waktu >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY bulan ORDER BY bulan
");
while ($row = mysqli_fetch_assoc($kunjunganBulan)) {
    $bulanLabelsKunjungan[] = $row['bulan'];
    $bulanCountsKunjungan[] = $row['jumlah'];
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Analitik</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f2f4f8; margin: 0; padding: 20px; }
        h2 { color: #0d47a1; }
        .card {
            background: white; padding: 20px; margin-bottom: 30px;
            border-radius: 10px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px;
        }
        th, td {
            padding: 10px; border: 1px solid #ddd; text-align: left;
        }
        th {
            background-color: #e3f2fd; color: #0d47a1;
        }
        canvas {
            max-width: 100%; margin-top: 15px;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #0d47a1;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 10px;
        }
        .back-btn:hover {
            background: #1565c0;
        }
    </style>
</head>
<body>

<h2>📊 Dashboard Analitik Digi-Logbook RSUD Sekarwangi</h2>

<div class="card">
    <h3>Total Entri Logbook</h3>
    <p><strong><?= $totalLogbook ?></strong> entri telah dikumpulkan.</p>
</div>

<div class="card">
    <h3>📌 Jumlah Entri per Unit/Ruangan</h3>
    <canvas id="chartRuangan"></canvas>
    <table>
        <thead>
            <tr><th>Unit</th><th>Jumlah</th></tr>
        </thead>
        <tbody>
        <?php foreach ($ruanganLabels as $i => $r): ?>
            <tr>
                <td><?= htmlspecialchars($r) ?></td>
                <td><?= $ruanganCounts[$i] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>👥 Petugas dan Jumlah Entri</h3>
    <canvas id="chartPetugas"></canvas>
    <table id="petugasTable">
        <thead>
            <tr><th>Nama</th><th>Jumlah Entri</th></tr>
        </thead>
        <tbody>
        <?php foreach ($userData as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= $row['total'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>📈 Tren Pengisian Logbook (12 Bulan Terakhir)</h3>
    <canvas id="chartBulan"></canvas>
</div>

<div class="card">
    <h3>🕒 Kunjungan Aplikasi per Hari (7 Hari Terakhir)</h3>
    <canvas id="chartKunjunganHari"></canvas>
    <table>
        <tr><th>Tanggal</th><th>Jumlah Kunjungan</th></tr>
        <?php foreach ($hariLabels as $i => $tgl): ?>
            <tr>
                <td><?= $tgl ?></td>
                <td><?= $hariCounts[$i] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="card">
    <h3>📆 Kunjungan Aplikasi per Bulan (12 Bulan Terakhir)</h3>
    <canvas id="chartKunjunganBulan"></canvas>
    <table>
        <tr><th>Bulan</th><th>Jumlah Kunjungan</th></tr>
        <?php foreach ($bulanLabelsKunjungan as $i => $bulan): ?>
            <tr>
                <td><?= $bulan ?></td>
                <td><?= $bulanCountsKunjungan[$i] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<a href="dashboard.php" class="back-btn">⬅ Kembali ke Dashboard</a>

<script>
    $(document).ready(function() {
        $('#petugasTable').DataTable({
            paging: false,
            searching: true,
            ordering: true,
            order: [[1, 'desc']]
        });
    });

    const ruanganLabels = <?= json_encode($ruanganLabels) ?>;
    const ruanganCounts = <?= json_encode($ruanganCounts) ?>;
    const userLabels = <?= json_encode(array_column($userData, 'nama')) ?>;
    const userCounts = <?= json_encode(array_column($userData, 'total')) ?>;
    const bulanLabels = <?= json_encode($bulanLabels) ?>;
    const bulanCounts = <?= json_encode($bulanCounts) ?>;
    const hariLabels = <?= json_encode($hariLabels) ?>;
    const hariCounts = <?= json_encode($hariCounts) ?>;
    const bulanLabelsKunjungan = <?= json_encode($bulanLabelsKunjungan) ?>;
    const bulanCountsKunjungan = <?= json_encode($bulanCountsKunjungan) ?>;

    new Chart(document.getElementById('chartRuangan'), {
        type: 'bar',
        data: {
            labels: ruanganLabels,
            datasets: [{
                label: 'Jumlah Entri',
                data: ruanganCounts,
                backgroundColor: '#42a5f5'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('chartPetugas'), {
        type: 'bar',
        data: {
            labels: userLabels,
            datasets: [{
                label: 'Jumlah Entri',
                data: userCounts,
                backgroundColor: '#66bb6a'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('chartBulan'), {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Entri per Bulan',
                data: bulanCounts,
                borderColor: '#ef5350',
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('chartKunjunganHari'), {
        type: 'line',
        data: {
            labels: hariLabels,
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: hariCounts,
                borderColor: '#29b6f6',
                backgroundColor: 'rgba(41, 182, 246, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('chartKunjunganBulan'), {
        type: 'bar',
        data: {
            labels: bulanLabelsKunjungan,
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: bulanCountsKunjungan,
                backgroundColor: '#ff7043'
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
</body>
</html>
