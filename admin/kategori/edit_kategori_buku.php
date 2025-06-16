<?php
include '../../includes/db.php';
// include '../../includes/sidebar.php';
session_start();
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM kategori WHERE id = $id");
$row = mysqli_fetch_assoc($query);


// Proses Update
if (isset($_POST['update'])) {
    $nama = $_POST['nama_kategori'];
    $deskripsi = $_POST['deskripsi'];
    $fotoLama = $row['foto_kategori'];

    if ($_FILES['foto_kategori']['error'] === 0) {
        $foto = $_FILES['foto_kategori']['name'];
        $tmp = $_FILES['foto_kategori']['tmp_name'];
        $path = '../../uploads/kategori/' . $foto;

        // Simpan file
        move_uploaded_file($tmp, $path);
    } else {
        $foto = $fotoLama; // jika tidak upload baru
    }

    $update = mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama', deskripsi='$deskripsi', foto_kategori='$foto' WHERE id=$id");

    if ($update) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Kategori berhasil diperbarui.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = 'kelola_kategori.php';
            });
        </script>";
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire('Gagal', 'Terjadi kesalahan saat memperbarui.', 'error');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Kategori Buku</h2>
        <div class="p-4 bg-light rounded shadow-lg">
            <form method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Kategori</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-bookmark-fill"></i></span>
                            <input type="text" name="nama_kategori"
                                value="<?= htmlspecialchars($row['nama_kategori']) ?>" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Deskripsi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                            <input type="text" name="deskripsi" value="<?= htmlspecialchars($row['deskripsi']) ?>"
                                required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto Kategori (kosongkan jika tidak ingin mengubah)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-image-fill"></i></span>
                            <input type="file" name="foto_kategori" class="form-control">
                        </div>
                        <small class="text-muted">Foto sekarang: <?= htmlspecialchars($row['foto'] ?? '') ?></small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Preview</label><br>
                        <img src="../../uploads/kategori/<?= htmlspecialchars($row['foto'] ?? '') ?>" width="100"
                            class="rounded shadow" alt="Foto Lama">
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between gap-2">
                    <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <a href="kelola_kategori_buku.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>