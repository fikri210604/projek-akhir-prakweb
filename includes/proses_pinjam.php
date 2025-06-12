<?php
include 'db.php';
session_start();

// Cek apakah sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$buku_id = $_POST['buku_id'];
$cekBuku = mysqli_query($conn, "SELECT * FROM buku WHERE id = $buku_id");
$dataBuku = mysqli_fetch_assoc($cekBuku);

if (!$dataBuku || $dataBuku['jumlah'] <= 0) {
    $pesan = "Buku tidak tersedia atau stok habis!";
    $tipe = "error";
    $redirect = "../user/dashboard_user.php";
} else {
    $tanggal_pinjam = date("Y-m-d");
    $tanggal_kembali = date("Y-m-d", strtotime("+7 days"));

    // Simpan data peminjaman
    $insert = mysqli_query($conn, "INSERT INTO peminjaman (users_id, buku_id, tanggal_pinjam, tanggal_kembali, status)
                                   VALUES ($user_id, $buku_id, '$tanggal_pinjam', '$tanggal_kembali', 'dipinjam')");

    if ($insert) {
        mysqli_query($conn, "UPDATE buku SET jumlah = jumlah - 1 WHERE id = $buku_id");
        $pesan = "Peminjaman berhasil!";
        $tipe = "success";
    } else {
        $pesan = "Terjadi kesalahan saat meminjam buku.";
        $tipe = "error";
    }

    $redirect = "../user/dashboard_user.php";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Proses Peminjaman</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    Swal.fire({
        icon: '<?= $tipe ?>',
        title: '<?= $pesan ?>',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = '<?= $redirect ?>';
    });
</script>
</body>
</html>
