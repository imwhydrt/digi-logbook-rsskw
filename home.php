<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

switch ($_SESSION['role']) {
    case 'petugas':
        header("Location: index.php");
        break;
    case 'admin':
    case 'supervisor':
        header("Location: dashboard.php");
        break;
    default:
        echo "Role tidak dikenali.";
        break;
}
exit();
?>
