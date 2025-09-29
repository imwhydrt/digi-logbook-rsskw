<?php
include 'db.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['reg_username']);
    $nama     = mysqli_real_escape_string($conn, $_POST['reg_nama']);
    $role     = mysqli_real_escape_string($conn, $_POST['reg_role']);
    $unit     = mysqli_real_escape_string($conn, $_POST['reg_unit']);
    $hashed_password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $sql = "INSERT INTO users (nama, username, password, role, unit) 
                VALUES ('$nama', '$username', '$hashed_password', '$role', '$unit')";
        if (mysqli_query($conn, $sql)) {
            $success = "Registrasi berhasil! Anda akan diarahkan ke halaman login...";
            header("refresh:3;url=login.php");
        } else {
            $error = "Registrasi gagal. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pengguna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #0d47a1;
            --accent: #1565c0;
            --background: #f7faff;
            --text: #333;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--background);
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 30px 25px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(12, 210, 255, 0.9);
        }

        h2 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: var(--text);
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #90caf9;
            background-color: #f1faff;
            font-size: 14px;
        }

        .button-primary {
            background-color: var(--primary);
            color: #fff;
            padding: 12px;
            margin-top: 25px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button-primary:hover {
            background-color: var(--accent);
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        @media (max-width: 480px) {
            .container {
                margin: 30px 15px;
                padding: 25px 20px;
            }

            input, select {
                font-size: 13px;
            }

            .button-primary {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📝 Registrasi Pengguna Baru</h2>

    <?php if ($success): ?>
        <p class="message success"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="message error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Nama Lengkap beserta Gelar:</label>
        <input type="text" name="reg_nama" required>

        <label>Username:</label>
        <input type="text" name="reg_username" required>

        <label>Password:</label>
        <input type="password" name="reg_password" required>

        <label>Role:</label>
        <select name="reg_role" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin">Admin</option>
            <option value="petugas">Petugas</option>
            <option value="supervisor">Supervisor</option>
        </select>

        <label>Unit/Ruangan:</label>
        <select name="reg_unit" required>
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

        <button type="submit" name="register" class="button-primary">✅ Daftar</button>
    </form>
</div>

</body>
</html>
