<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $user_id = $user['id'];
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        mysqli_query($conn, "INSERT INTO reset_tokens (user_id, token, expires_at) VALUES ('$user_id', '$token', '$expires')");

        $resetLink = "http://localhost/elogbook/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'imamwahyu250@gmail.com'; // Ganti sesuai akun
            $mail->Password   = 'xmpkdpqyzpdcqmoq';        // Ganti dengan App Password Google
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('your-email@gmail.com', 'Digi-Logbook');
            $mail->addAddress($email, $user['nama']);
            $mail->isHTML(true);
            $mail->Subject = '🔐 Reset Password Anda';
            $mail->Body    = "Halo <b>{$user['nama']}</b>,<br><br>Klik link berikut untuk reset password Anda:<br><br>
                <a href='$resetLink'>$resetLink</a><br><br>
                Link ini berlaku selama 1 jam.<br><br>Terima kasih.";

            $mail->send();
            $message = "<span style='color: green;'>✅ Link reset berhasil dikirim ke email Anda.</span>";
        } catch (Exception $e) {
            $message = "<span style='color: red;'>❌ Gagal mengirim email: {$mail->ErrorInfo}</span>";
        }
    } else {
        $message = "<span style='color: red;'>⚠️ Email tidak ditemukan dalam sistem.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary: #0d47a1;
            --accent: #1976d2;
            --background: #f1f7fe;
            --text: #333;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--background);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 30px 25px;
            border-radius: 20px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 6px 18px rgba(30, 190, 255, 0.6);
            text-align: center;
        }

        h2 {
            color: var(--primary);
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            margin-bottom: 20px;
            color: var(--text);
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            font-size: 15px;
            background-color: #f1faff;
        }

        button {
            width: 100%;
            background-color: var(--accent);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--primary);
        }

        .message {
            margin-top: 15px;
            font-size: 14px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #1565c0;
            text-decoration: none;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 25px 20px;
            }

            input[type="email"], button {
                font-size: 14px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔐 Reset Password</h2>
    <p>Masukkan email Anda untuk menerima link reset password.</p>

    <form method="POST">
        <input type="email" name="email" placeholder="Email terdaftar" required>
        <button type="submit">📩 Kirim Link Reset</button>
    </form>

    <div class="message"><?= $message ?></div>

    <a href="login.php">← Kembali ke Login</a>
</div>

</body>
</html>
