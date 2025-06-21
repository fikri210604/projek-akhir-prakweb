<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_POST['id'];
$tanggal_pinjam = $_POST['tanggal_pinjam'];
$tanggal_kembali = $_POST['tanggal_kembali'];

// Validasi sederhana
if (!$id || !$tanggal_pinjam || !$tanggal_kembali) {
    die("Data tidak lengkap.");
}

// Validasi logika tanggal
$today = date('Y-m-d');

if ($tanggal_pinjam < $today || $tanggal_kembali < $tanggal_pinjam) {
    echo "<script>alert('Tanggal tidak sesuai.'); window.history.back();</script>";
    exit;
}

// Update ke database jika tanggal valid
$query = "UPDATE peminjaman 
          SET tanggal_pinjam = '$tanggal_pinjam', tanggal_kembali = '$tanggal_kembali' 
          WHERE id = $id AND users_id = {$_SESSION['user_id']}";

if (mysqli_query($conn, $query)) {
    header("Location: ../user/dashboard_user.php?success=1");
    exit;
} else {
    echo "Gagal update: " . mysqli_error($conn);
}
