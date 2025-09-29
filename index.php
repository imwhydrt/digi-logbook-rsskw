<?php include 'auth.php'; ?>
<?php if ($_SESSION['role'] != 'petugas') { echo "Akses ditolak!"; exit(); } ?>
<?php include 'navbar.php'; ?>

<?php
$nama_petugas = $_SESSION['nama'];
$unit_petugas = $_SESSION['unit'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Digi-Logbook</title>
    <!-- favicon for all browsers -->
    <link rel="icon" href="logo.png" type="image/png">
    <link rel="shortcut icon" href="logo.png" type="image/png">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <!-- HTML5 favicon -->
    <link rel="icon" sizes="32x32" href="logo.png">
    <link rel="apple-touch-icon" href="logo.png">
    <link rel="icon" type="image/x-icon" href="/elogbook/favicon.ico" />


    
    <style>
        :root {
            --primary: #0d47a1;
            --secondary: #e3f2fd;
            --accent: #1565c0;
            --text: #333;
            --border: #ddd;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--secondary);
            margin: 0;
            padding: 15px;
            color: var(--text);
        }

        h2 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        form {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            max-width: 720px;
            margin: auto;
            box-shadow: 0 6px 12px rgba(12, 184, 247, 0.99);
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 15px;
            background: #fafafa;
        }

        input[readonly] {
            background-color: #f0f0f0;
            color: #555;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        button {
            width: 100%;
            background-color: var(--primary);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            margin-top: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--accent);
        }

        .link-bar {
            text-align: center;
            margin-top: 30px;
        }

        .link-bar a {
            color: var(--primary);
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            form {
                padding: 20px;
                margin: 10px auto;
            }

            h2 {
                font-size: 1.5rem;
            }

            button {
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            input, select, textarea, button {
                font-size: 14px;
            }

            label {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<h2>📋 Form Logbook Keperawatan</h2>

<form method="POST" action="simpan.php">

    <label>Nama Perawat / Bidan:</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($nama_petugas) ?>" readonly>

    <label>Unit Kerja / Ruangan:</label>
    <input type="text" name="unit" value="<?= htmlspecialchars($unit_petugas) ?>" readonly>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" required>

    <label>Intervensi:</label>
    <textarea name="intervensi" required></textarea>

    <label>No. RM (Rekam Medis):</label>
    <input type="text" name="no_rm" required>

    <label>Patuh SPO:</label>
    <select name="spo" required>
        <option value="Ya">Ya</option>
        <option value="Tidak">Tidak</option>
    </select>

    <label>Nama Atasan / Kepala Ruangan:</label>
    <select name="ttd" required>
        <option value="">-- Pilih Atasan --</option>
        <?php
        $atasan = [
            "Iyud Wahyudi, S.Kep., Ners", "Agus Sugiarto, Am.Kep", "Siti Rahayu, S.Kep.Ners",
            "Riki Kuswandi, S.Kep.Ners, M.Kep", "Resti Agustina, S.Kep.Ners", "Reni Yuliani, S.Kep.Ners",
            "Eli Irianti, S.Kep.Ners", "Ika Sulistiawati, S.Kep.Ners, M.Kep", "Roni Virgo, S.Kep.Ners",
            "Hesti Darojatun, Am.Keb", "Andri Kusmayadi, S.Kep.Ners", "Sri Ismaya, AMK",
            "Ai Aibah, S.Kep.Ners", "Ai Herawati, S.Kep.Ners", "Yeni Rustiani, S.Kep.Ners",
            "Indah Martiastuti H, Am.Keb", "Emis Rukami, S.Kep.Ners", "Popi Rohyati, S.Kep.Ners",
            "Dewi Pusphasari, S.Kep.Ners", "Diana Mulyasari, S.Kep.Ners", "Sartika Dewi Kusmiati, Amd.Keb",
            "Deden Anton Zamzam, S.Kep", "Dessi Siti Fatimah, AM.Kep"
        ];
        foreach ($atasan as $nama) {
            echo "<option>$nama</option>";
        }
        ?>
    </select>

    <label>Shift:</label>
    <select name="shift" required>
        <option value="Pagi">Pagi</option>
        <option value="Siang">Siang</option>
        <option value="Malam">Malam</option>
    </select>

    <button type="submit">💾 Simpan Logbook</button>
</form>

<div class="link-bar">
    <a href="dashboard.php">📊 Lihat Data</a> | 
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>
