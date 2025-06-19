<?php
include 'db.php';
var_dump($_POST);

session_start();

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    if (empty($tanggal_pinjam) || empty($tanggal_kembali)) {
        echo "<script>alert('Tanggal pinjam dan kembali wajib diisi!');window.location='dashboard_user.php';</script>";
        exit();
    }

    if (strtotime($tanggal_kembali) < strtotime($tanggal_pinjam)) {
        echo "<script>alert('Tanggal kembali tidak boleh lebih awal dari tanggal pinjam!');window.location='dashboard_user.php';</script>";
        exit();
    }
    $update = mysqli_query($conn, "UPDATE peminjaman 
                                   SET tanggal_pinjam = '$tanggal_pinjam', 
                                       tanggal_kembali = '$tanggal_kembali' 
                                   WHERE id = $id");

    if ($update) {
        echo "<script>alert('Data berhasil diperbarui');window.location='dashboard_user.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data');window.location='dashboard_user.php';</script>";
    }
}
?>
