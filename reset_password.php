<?php
include 'db.php';
$token = $_GET['token'] ?? '';
$success = '';
$error = '';

if ($token) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM reset_tokens WHERE token = ?");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row || strtotime($row['expires_at']) < time()) {
        $error = "⛔ Token tidak valid atau sudah kedaluwarsa.";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $error = "⚠️ Password tidak cocok.";
        } elseif (strlen($new_password) < 6) {
            $error = "⚠️ Password minimal 6 karakter.";
        } else {
            $user_id = $row['user_id'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password
            $update = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($update, "si", $hashed_password, $user_id);
            mysqli_stmt_execute($update);

            // Hapus token
            mysqli_query($conn, "DELETE FROM reset_tokens WHERE user_id = '$user_id'");

            $success = "✅ Password berhasil diubah. Silakan login.";
        }
    }
} else {
    $error = "⛔ Token tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #e3f2fd;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 6px 16px rgba(35, 172, 235, 0.97);
            text-align: center;
        }
        h2 {
            color: #0d47a1;
        }
        input[type=password] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            margin-top: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            color: white;
            background-color: #1976d2;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1565c0;
        }
        .message {
            margin-top: 20px;
            font-size: 14px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
        a {
            display: block;
            margin-top: 20px;
            color: #1976d2;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @media (max-width: 480px) {
            .container {
                margin: 20px 15px;
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔒 Reset Password</h2>

    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
        <a href="login.php">← Kembali ke Login</a>
    <?php elseif ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!$success && !$error || ($token && !$success)): ?>
    <form method="POST">
        <input type="password" name="password" placeholder="Password Baru" required>
        <input type="password" name="confirm_password" placeholder="Ulangi Password Baru" required>
        <button type="submit">🔁 Ubah Password</button>
    </form>
    <?php endif; ?>
</div>

</body>
</html>
