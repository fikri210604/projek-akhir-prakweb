<?php
session_start();
include '../../includes/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Cek user berdasarkan nama
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
$data = mysqli_fetch_assoc($query);

if ($data && password_verify($password, $data['password'])) {
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['login'] = 'true';

    if ($data['role'] == 'petugas') {
        header('Location: ../../admin/dashboard.php');
    } else {
        header('Location: ../../user/dashboard_user.php');
    }
    exit();
} else {
    $_SESSION['error'] = "Nama atau password salah!";
    header('Location: ../../login.php');
    exit();
}
?>
