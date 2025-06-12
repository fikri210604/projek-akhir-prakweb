<?php
include '../../includes/db.php';
// include '../../includes/sidebar.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM buku WHERE id = $id");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Edit Buku</h2>
    <div class="bg-light p-4 rounded shadow">
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="judul" value="<?= $data['judul'] ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Penulis</label>
                <input type="text" name="penulis" value="<?= $data['penulis'] ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" value="<?= $data['penerbit'] ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="<?= $data['tahun_terbit'] ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select" required>
                    <?php
                    $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                    while ($k = mysqli_fetch_assoc($kategori)) {
                        $selected = $k['id'] == $data['kategori_id'] ? 'selected' : '';
                        echo "<option value='{$k['id']}' $selected>{$k['nama_kategori']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="tersedia" <?= $data['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="dipinjam" <?= $data['status'] == 'dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Buku</label><br>
                <?php if ($data['foto']): ?>
                    <img src="../../uploads/buku/<?= $data['foto'] ?>" width="100" class="mb-2"><br>
                <?php endif; ?>
                <input type="file" name="foto" accept="image/*" class="form-control">
                <input type="hidden" name="foto_lama" value="<?= $data['foto'] ?>">
            </div>
            <button type="submit" name="update" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
            <a href="data_buku.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?php
if (isset($_POST['update'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun_terbit'];
    $kategori = $_POST['kategori_id'];
    $jumlah = $_POST['jumlah'];
    $status = $_POST['status'];
    $foto_lama = $_POST['foto_lama'];

    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];

    if (!empty($foto)) {
        $folder = "../../uploads/buku/";
        if (!is_dir($folder)) mkdir($folder, 0777, true);
        move_uploaded_file($tmp, $folder . $foto);
        $foto_simpan = $foto;
        if ($foto_lama && file_exists($folder . $foto_lama)) {
            unlink($folder . $foto_lama);
        }
    } else {
        $foto_simpan = $foto_lama;
    }

    $update = mysqli_query($conn, "UPDATE buku SET 
        judul='$judul', penulis='$penulis', penerbit='$penerbit', tahun_terbit='$tahun',
        kategori_id='$kategori', jumlah='$jumlah', status='$status', foto='$foto_simpan'
        WHERE id=$id");

    if ($update) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data buku berhasil diperbarui.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = 'kelola_buku.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui.', 'error');
        </script>";
    }
}
?>
</body>
</html>
