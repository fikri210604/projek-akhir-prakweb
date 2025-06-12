<?php
include '../../includes/db.php';
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM kategori WHERE id = $id");
echo "<script>alert('Data berhasil dihapus');window.location='kelola_kategori_buku.php';</script>";
?>
