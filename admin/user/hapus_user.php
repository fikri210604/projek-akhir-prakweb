<?php
include '../../includes/db.php';
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM users WHERE id = $id");
echo "<script>alert('Data berhasil dihapus');window.location='data_user.php';</script>";
?>
