<?php
require 'vendor/autoload.php';
include 'auth.php';
include 'db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Validasi Role
if (!in_array($_SESSION['role'], ['admin', 'supervisor', 'petugas'])) {
    echo "Akses ditolak!";
    exit();
}

// Ambil informasi pengguna
$namaUser = $_SESSION['nama'];
$unitUser = $_SESSION['unit'];
$roleUser = $_SESSION['role'];

// Buat WHERE klausa filter
$where = "WHERE 1=1";

// Filter tanggal
$periode = "Semua Periode";
if (!empty($_GET['tanggal_awal']) && !empty($_GET['tanggal_akhir'])) {
    $awal = mysqli_real_escape_string($conn, $_GET['tanggal_awal']);
    $akhir = mysqli_real_escape_string($conn, $_GET['tanggal_akhir']);
    $where .= " AND tanggal BETWEEN '$awal' AND '$akhir'";
    $periode = date('d-m-Y', strtotime($awal)) . " s.d " . date('d-m-Y', strtotime($akhir));
}

// Filter role supervisor
if ($roleUser == 'supervisor') {
    $where .= " AND unit = '" . mysqli_real_escape_string($conn, $unitUser) . "'";
}

// Filter role petugas
if ($roleUser == 'petugas') {
    $where .= " AND nama = '" . mysqli_real_escape_string($conn, $namaUser) . "'";
}

// Filter nama
if (!empty($_GET['cari_nama'])) {
    $cari_nama = mysqli_real_escape_string($conn, $_GET['cari_nama']);
    $where .= " AND nama LIKE '%$cari_nama%'";
}

// Filter unit (admin)
if ($roleUser == 'admin' && !empty($_GET['cari_unit'])) {
    $cari_unit = mysqli_real_escape_string($conn, $_GET['cari_unit']);
    $where .= " AND unit LIKE '%$cari_unit%'";
}

// Ambil logo dan ubah jadi base64
$logoPath = 'logo_skw.jpg';
$logoData = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/jpeg;base64,' . $logoData;

// Set zona waktu Jakarta
date_default_timezone_set('Asia/Jakarta');
$tanggalCetak = date('d-m-Y H:i');

// Awal HTML
$html = '
<html>
<head>
<style>
    @page {
        margin: 120px 40px 80px 40px;
    }

    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    header {
        position: fixed;
        top: -100px;
        left: 0;
        right: 0;
        height: 100px;
    }

    .header-table {
        width: 100%;
    }

    .header-table td {
        vertical-align: middle;
        text-align: center;
    }

    .header-logo {
        width: 80px;
        padding-left: 10px;
    }

    .header-title h3 {
        margin: 0;
        font-size: 18px;
    }

    .header-title small {
        font-size: 13px;
    }

    .info-table {
        margin-top: -40px;
        margin-bottom: 10px;
        font-size: 13px;
    }

    .info-table td {
        padding: 2px 5px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table, th, td {
        border: 1px solid #ccc;
    }

    th {
        background-color: #e0f0ff;
        text-align: center;
    }

    td {
        vertical-align: top;
        padding: 4px;
    }
</style>

</head>
<body>

<header>
    <table class="header-table">
        <tr>
            <td class="header-logo" width="100">
                <img src="' . $logoSrc . '" alt="Logo" height="80">
            </td>
            <td class="header-title">
                <h3>RSUD Sekarwangi</h3>
                <small>Data Logbook Keperawatan</small>
            </td>
        </tr>
    </table>
</header>

<br></br>
<br></br>
<table class="info-table">
    <tr>
        <td><strong>Nama Pengunduh</strong></td>
        <td>: ' . htmlspecialchars($namaUser) . '</td>
    </tr>
    <tr>
        <td><strong>Unit / Ruangan</strong></td>
        <td>: ' . htmlspecialchars($unitUser) . '</td>
    </tr>
    <tr>
        <td><strong>Periode</strong></td>
        <td>: ' . $periode . '</td>
    </tr>
</table>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Petugas</th>
    <th>Unit</th>
    <th>Tanggal</th>
    <th>Intervensi</th>
    <th>No. RM</th>
    <th>SPO</th>
    <th>Shift</th>
    <th>Supervisor</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
';

$query = mysqli_query($conn, "SELECT * FROM logbook $where ORDER BY tanggal DESC");
$no = 1;
$supervisorName = "";
$petugasName = "";

if (mysqli_num_rows($query) == 0) {
    $html .= '<tr><td colspan="10" style="text-align:center;">Tidak ada data ditemukan</td></tr>';
} else {
    while ($row = mysqli_fetch_assoc($query)) {
        $html .= "<tr>
            <td align='center'>{$no}</td>
            <td>{$row['nama']}</td>
            <td>{$row['unit']}</td>
            <td align='center'>{$row['tanggal']}</td>
            <td>{$row['intervensi']}</td>
            <td align='center'>{$row['no_rm']}</td>
            <td align='center'>{$row['spo']}</td>
            <td align='center'>{$row['shift']}</td>
            <td>{$row['ttd']}</td>
            <td align='center'>{$row['status']}</td>
        </tr>";
        if ($supervisorName == "") {
            $supervisorName = $row['ttd'];
            $petugasName = $row['nama'];
        }
        $no++;
    }
}

$html .= "</tbody></table>";

// Tanda tangan
if ($roleUser == 'admin') {
    $html .= "<br><br><table width='100%' style='font-size:13px;'>
    <tr>
        <td width='50%'></td>
        <td width='50%' style='text-align:center;'>
            Manager<br><br><br><br><br>
            <strong><u>" . htmlspecialchars($namaUser) . "</u></strong>
        </td>
    </tr>
    </table>";
} else {
    $html .= "<br><br><table width='100%' style='font-size:13px; text-align:center;'>
    <tr>
        <td width='50%'>
            Supervisor / Kepala Ruangan<br><br><br><br><br>
            <strong><u>" . htmlspecialchars($supervisorName) . "</u></strong>
        </td>
        <td width='50%'>
            Petugas yang Mengisi<br><br><br><br><br>
            <strong><u>" . htmlspecialchars($petugasName) . "</u></strong>
        </td>
    </tr>
    </table>";
}

$html .= "</body></html>";

// DomPDF Render
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->setIsHtml5ParserEnabled(true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Tambahkan nomor halaman dan waktu cetak ke footer dengan canvas
$canvas = $dompdf->getCanvas();
$footerText = "Dicetak pada: $tanggalCetak | Halaman {PAGE_NUM} dari {PAGE_COUNT}";
$canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($footerText, $tanggalCetak) {
    $text = "Dicetak pada: $tanggalCetak | Halaman $pageNumber dari $pageCount";
    $font = $fontMetrics->getFont('Helvetica', 'normal');
    $size = 9;
    $width = $fontMetrics->getTextWidth($text, $font, $size);
    $canvas->text(
        ($canvas->get_width() - $width) / 2,
        $canvas->get_height() - 30,
        $text,
        $font,
        $size
    );
});

$dompdf->stream("logbook.pdf");
?>
