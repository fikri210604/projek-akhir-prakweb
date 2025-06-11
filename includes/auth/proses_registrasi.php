<?php
session_start();
require_once '../db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['email']);
    $nama       = trim($_POST['nama']);
    $no_telp    = trim($_POST['nomor_telepon']);
    $role       = trim($_POST['role']);
    $password   = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    // Validasi input tidak kosong
    if (empty($email) || empty($nama) || empty($no_telp) || empty($role) || empty($password) || empty($konfirmasi)) {
        $_SESSION['error'] = "Semua field wajib diisi!";
        header("Location: ../../register.php");
        exit;
    }

    // Cek konfirmasi password
    if ($password !== $konfirmasi) {
        $_SESSION['error'] = "Konfirmasi password tidak sesuai!";
        header("Location: ../../register.php");
        exit;
    }

    // Cek apakah email sudah terdaftar
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Email sudah digunakan!";
        header("Location: ../../register.php");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $query = "INSERT INTO users (email, nama, nomor_telepon, password, role) 
              VALUES ('$email', '$nama', '$no_telp', '$hashed_password', '$role')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header("Location: ../../login.php");
        exit;
    } else {
        $_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($conn);
        header("Location: ../../register.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Akses tidak valid!";
    header("Location: ../../register.php");
    exit;
}
