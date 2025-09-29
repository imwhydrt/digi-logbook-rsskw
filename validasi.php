<?php
include 'auth.php';
include 'db.php';

if ($_SESSION['role'] != 'supervisor') {
    echo "Akses ditolak!";
    exit();
}

if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id = intval($_GET['id']);
    $aksi = $_GET['aksi'] == 'approve' ? 'Disetujui' : 'Ditolak';
    mysqli_query($conn, "UPDATE logbook SET status='$aksi' WHERE id=$id");
    header("Location: validasi.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Validasi Logbook</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<h2>Halaman Validasi Supervisor</h2>
<a href="logout.php" class="logout-button">Logout</a>
><br><br>

<table border="1" cellpadding="5">
    <tr>
        <th>Nama</th><th>Tanggal</th><th>Intervensi</th><th>Status</th><th>Aksi</th>
    </tr>
    <?php
    $result = mysqli_query($conn, "SELECT * FROM logbook ORDER BY tanggal DESC");
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['nama']}</td>";
        echo "<td>{$row['tanggal']}</td>";
        echo "<td>{$row['intervensi']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>";
        if ($row['status'] == 'Belum Divalidasi') {
            echo "<a href='validasi.php?id={$row['id']}&aksi=approve'>✔️ Setujui</a> | ";
            echo "<a href='validasi.php?id={$row['id']}&aksi=reject'>❌ Tolak</a>";
        } else {
            echo "-";
        }
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>
