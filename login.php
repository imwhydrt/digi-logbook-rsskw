<?php
session_start();
include 'db.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['unit'] = $user['unit'];

            $user_id = $user['id'];
            $nama = mysqli_real_escape_string($conn, $user['nama']);
            $role = mysqli_real_escape_string($conn, $user['role']);

            mysqli_query($conn, "INSERT INTO log_kunjungan (user_id, nama, role) VALUES ('$user_id', '$nama', '$role')");

            if ($user['role'] === 'petugas') {
                header("Location: index.php");
            } elseif ($user['role'] === 'admin' || $user['role'] === 'supervisor') {
                header("Location: dashboard.php");
            } else {
                $error = "Role tidak dikenali.";
            }
            exit();
        } else {
            $error = "Login gagal! Password salah.";
        }
    } else {
        $error = "Login gagal! Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Digi-Logbook</title>
    <link rel="icon" type="image/png" href="/elogbook/logo_skw.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #0d47a1;
            --secondary: #e3f2fd;
            --accent: #1565c0;
            --text: #333;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--secondary);
            font-family: 'Segoe UI', sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            border-radius: 20px;
            padding: 30px 25px;
            box-shadow: 0 8px 20px rgba(12, 210, 255, 0.99);
            text-align: center;
        }

        .logo {
            width: 100px;
            margin-bottom: 10px;
        }

        h2 {
            color: var(--primary);
            margin: 5px 0;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: var(--text);
            font-size: 14px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #90caf9;
            border-radius: 8px;
            background-color: #f3f9ff;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: #fff;
            border: none;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--accent);
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin: 10px 0;
        }

        .link {
            display: block;
            margin-top: 10px;
            color: var(--accent);
            font-size: 14px;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }

        .register-button {
            display: inline-block;
            background-color: #03a9f4;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .register-button:hover {
            background-color: #0288d1;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 40px 15px;
                padding: 25px 20px;
            }

            h2 {
                font-size: 1.4rem;
            }

            input, button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <img src="Logo.png" alt="Logo RSUD Sekarwangi" class="logo">
    <h2>Digi-Logbook</h2>
    <h2>RSUD Sekarwangi</h2>

    <form method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <?php if (!empty($error)) echo "<div class='error-message'>$error</div>"; ?>

        <button type="submit">🔒 Login</button>

        <a href="forgot_password.php" class="link">🔑 Lupa Password?</a>
    </form>

    <a href="register.php" class="register-button">📝 Daftar Akun Baru</a>
</div>

</body>
</html>
