<?php
include 'auth.php';
include 'db.php';

// Gunakan session yang benar: user_id
if (!isset($_SESSION['user_id'])) {
    die("Akses tidak sah. Silakan login terlebih dahulu.");
}

$id_user = $_SESSION['user_id']; // Perbaikan dari $_SESSION['id']
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'");
$data = mysqli_fetch_assoc($query);

// Tangani proses update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $unit     = mysqli_real_escape_string($conn, $_POST['unit']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    $update = "UPDATE users SET 
        nama='$nama', username='$username', email='$email', unit='$unit', role='$role'";

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update .= ", password='$password'";
    }

    $update .= " WHERE id='$id_user'";
    mysqli_query($conn, $update);

    // Perbarui session juga (agar navbar tetap update)
    $_SESSION['nama'] = $nama;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
    $_SESSION['unit'] = $unit;

    header("Location: profil.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <link rel="icon" type="image/png" href="/elogbook/logo_skw.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 136, 248, 1);
        }
        h2 {
            margin-top: 0;
            color: #0d47a1;
            font-size: 24px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            background-color: #0d47a1;
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #1565c0;
        }
        .alert {
            background: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 15px;
                padding: 50px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2>👤 Profil Pengguna</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert">✅ Data berhasil diperbarui!</div>
        <?php endif; ?>

        <form method="POST">
            <label>Nama Lengkap dan Gelar:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>

            <label>Password Baru (kosongkan jika tidak diganti):</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah">

            <label for="unit">Unit Kerja / Ruangan:</label>
                <select name="unit" id="unit" required>
                <option value="">-- Pilih Ruangan --</option>
                <option value="Ade Irma Suryani Lt.1">Ade Irma Suryani Lt.1</option>
                <option value="Ade Irma Suryani Lt.2">Ade Irma Suryani Lt.2</option>
                <option value="Aisyah Lt.1">Aisyah Lt.1</option>
                <option value="Aisyah Lt.2">Aisyah Lt.2</option>
                <option value="Cut Nyak Dien">Cut Nyak Dien</option>
                <option value="Cut Meutia">Cut Meutia</option>
                <option value="Inggit Garnasih">Inggit Garnasih</option>
                <option value="Kartini">Kartini</option>
                <option value="Nyi Ahmad Dahlan">Nyi Ahmad Dahlan</option>
                <option value="NICU">NICU</option>
                <option value="Fatmawati Lt.1">Fatmawati Lt.1</option>
                <option value="Fatmawati Lt.2">Fatmawati Lt.2</option>
                <option value="Nyi Ageng Serang Lt.1">Nyi Ageng Serang Lt.1</option>
                <option value="Nyi Ageng Serang Lt.2">Nyi Ageng Serang Lt.2</option>
                <option value="Siti Khadijah">Siti Khadijah</option>
                <option value="PICU">PICU</option>
                <option value="Instalasi Bedah Sentral">Instalasi Bedah Sentral</option>
                <option value="Instalagi Gawat Darurat">Instalagi Gawat Darurat</option>
                <option value="Instalasi Rawat Jalan">Instalasi Rawat Jalan</option>
                <option value="Raden Dewi Sartika">Raden Dewi Sartika</option>
                <option value="Rasuna Said">Rasuna Said</option>
                <option value="Wijaya Kusuma">Wijaya Kusuma</option>
                <option value="Manajer">Manajer</option>
                </select>

            <label>Role:</label>
            <select name="role" required>
                <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="supervisor" <?= $data['role'] == 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                <option value="petugas" <?= $data['role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
            </select>

            <button type="submit">💾 Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
