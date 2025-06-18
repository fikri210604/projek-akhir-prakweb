<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'petugas') {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db.php';
include '../asset/navbar.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM penulis WHERE id = $id");
$penulis = mysqli_fetch_assoc($data);

if (!$penulis) {
    echo "<script>
        alert('Data tidak ditemukan!');
        window.location = 'kelola_penulis.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Penulis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>

<body style="background-color: #f8f9fa;">
<div class="container mt-4">
    <div class="p-4 bg-light rounded shadow mb-4">
        <h2>Edit Penulis</h2>
    </div>

    <div class="p-4 bg-light rounded shadow">
        <form method="post" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Nama Penulis</label>
                    <input type="text" name="nama_penulis" class="form-control" required value="<?= htmlspecialchars($penulis['nama_penulis']) ?>">
                </div>
                <div class="col-md-6">
                    <label>Biografi</label>
                    <input type="text" name="bio" class="form-control" required value="<?= htmlspecialchars($penulis['biografi']) ?>">
                </div>
                <div class="col-md-6">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" required value="<?= $penulis['tanggal_lahir'] ?>">
                </div>
                <div class="col-md-6">
                    <label>Kebangsaan</label>
                    <input type="text" name="kebangsaan" class="form-control" required value="<?= htmlspecialchars($penulis['kebangsaan']) ?>">
                </div>
                <div class="col-md-6">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="Laki-Laki" <?= $penulis['jenis_kelamin'] == 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                        <option value="Perempuan" <?= $penulis['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Foto Penulis Saat Ini</label><br>
                    <?php if (!empty($penulis['foto_penulis'])): ?>
                        <img src="../../uploads/penulis/<?= htmlspecialchars($penulis['foto_penulis']) ?>" width="100" class="img-thumbnail">
                    <?php else: ?>
                        <p class="text-muted">Tidak ada foto</p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label>Ganti Foto Penulis (opsional)</label>
                    <input type="file" name="foto_penulis" accept="image/*" class="form-control">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <a href="kelola_penulis.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_POST['update'])) {
    $nama_penulis   = mysqli_real_escape_string($conn, $_POST['nama_penulis']);
    $biografi       = mysqli_real_escape_string($conn, $_POST['bio']);
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $kebangsaan     = mysqli_real_escape_string($conn, $_POST['kebangsaan']);
    $jenis_kelamin  = $_POST['jenis_kelamin'];

    $foto_name = $_FILES['foto_penulis']['name'];
    $foto_tmp = $_FILES['foto_penulis']['tmp_name'];
    $foto_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

    // Default to old photo
    $new_foto_name = $penulis['foto_penulis'];

    // If new photo uploaded
    if (!empty($foto_name) && in_array($foto_ext, $allowed_ext)) {
        $new_foto_name = uniqid('penulis_', true) . '.' . $foto_ext;
        $upload_path = '../../uploads/penulis/' . $new_foto_name;
        move_uploaded_file($foto_tmp, $upload_path);

        // Hapus foto lama
        if (!empty($penulis['foto_penulis']) && file_exists('../../uploads/penulis/' . $penulis['foto_penulis'])) {
            unlink('../../uploads/penulis/' . $penulis['foto_penulis']);
        }
    }

    $update = mysqli_query($conn, "UPDATE penulis SET 
        nama_penulis = '$nama_penulis',
        biografi = '$biografi',
        tanggal_lahir = '$tanggal_lahir',
        kebangsaan = '$kebangsaan',
        jenis_kelamin = '$jenis_kelamin',
        foto_penulis = '$new_foto_name'
        WHERE id = $id");

    if ($update) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data penulis berhasil diperbarui.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = 'kelola_penulis.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire('Gagal', 'Gagal menyimpan perubahan.', 'error');
        </script>";
    }
}
?>
</body>
</html>
