<?php
include 'db.php';

// Ambil semua data user
$query = mysqli_query($conn, "SELECT * FROM users");
while ($user = mysqli_fetch_assoc($query)) {
    // Cek apakah password belum di-hash (tidak dimulai dengan $2y$)
    if (strpos($user['password'], '$2y$') !== 0) {
        $originalPassword = $user['password'];
        $hashed = password_hash($originalPassword, PASSWORD_DEFAULT);

        // Update password di database
        mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id={$user['id']}");
        echo "Password untuk user <strong>{$user['username']}</strong> berhasil dienkripsi.<br>";
    }
}
echo "<br><strong>Selesai. Semua password sudah dienkripsi (jika belum).</strong>";
?>
