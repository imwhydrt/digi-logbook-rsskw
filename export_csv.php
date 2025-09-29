<?php
include 'db.php';

$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';

$where = "WHERE 1=1";
if (!empty($start) && !empty($end)) {
    $where .= " AND tanggal BETWEEN '$start' AND '$end'";
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=logbook_export.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['Nama', 'Unit', 'Tanggal', 'Intervensi', 'No. RM', 'SPO', 'Shift', 'Supervisor', 'Status']);

$query = mysqli_query($conn, "SELECT * FROM logbook $where");
while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['nama'], $row['unit'], $row['tanggal'], $row['intervensi'],
        $row['no_rm'], $row['spo'], $row['shift'], $row['ttd'], $row['status']
    ]);
}

fclose($output);
exit();
?>
