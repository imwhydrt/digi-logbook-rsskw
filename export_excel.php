<?php
include 'db.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=logbook_export.xls");

$where = "WHERE 1=1";

if (!empty($_GET['start']) && !empty($_GET['end'])) {
    $start = $_GET['start'];
    $end = $_GET['end'];
    $where .= " AND tanggal BETWEEN '$start' AND '$end'";
}

if (!empty($_GET['nama'])) {
    $nama = mysqli_real_escape_string($conn, $_GET['nama']);
    $where .= " AND nama LIKE '%$nama%'";
}

if (!empty($_GET['unit'])) {
    $unit = mysqli_real_escape_string($conn, $_GET['unit']);
    $where .= " AND unit LIKE '%$unit%'";
}

$query = mysqli_query($conn, "SELECT * FROM logbook $where ORDER BY tanggal DESC");

echo "<table border='1'>";
echo "<tr><th>Nama</th><th>Unit</th><th>Tanggal</th><th>Intervensi</th><th>No. RM</th><th>SPO</th><th>Shift</th><th>Supervisor</th><th>Status</th></tr>";

while ($row = mysqli_fetch_assoc($query)) {
    echo "<tr>
        <td>{$row['nama']}</td>
        <td>{$row['unit']}</td>
        <td>{$row['tanggal']}</td>
        <td>{$row['intervensi']}</td>
        <td>{$row['no_rm']}</td>
        <td>{$row['spo']}</td>
        <td>{$row['shift']}</td>
        <td>{$row['ttd']}</td>
        <td>{$row['status']}</td>
    </tr>";
}
echo "</table>";
?>
