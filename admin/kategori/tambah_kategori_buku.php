<?php
include '../../includes/db.php';
include '../asset/sidebar.php';

if (isset($_POST['simpan'])) {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Handle upload foto
    $foto = $_FILES['foto_kategori']['name'];
    $tmp = $_FILES['foto_kategori']['tmp_name'];
    $folder = '../../uploads/kategori/';

    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $nama_baru = uniqid() . '_' . $foto;
    $upload_path = $folder . $nama_baru;

    if (move_uploaded_file($tmp, $upload_path)) {
        $query = "INSERT INTO kategori (nama_kategori, deskripsi, foto_kategori) VALUES ('$nama_kategori', '$deskripsi', '$nama_baru')";
        $insert = mysqli_query($conn, $query);
    } else {
        $insert = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body">
    <div class="container mt-5">
        <div class="p-4 bg-white rounded shadow-lg">
            <h3 class="mb-4">Tambah Kategori</h3>

            <form method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                            <input type="text" id="deskripsi" name="deskripsi" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="foto_kategori" class="form-label">Foto Kategori</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                            <input type="file" id="foto_kategori" name="foto_kategori" class="form-control"
                                accept="image/*" required>
                        </div>
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

    <!-- SweetAlert -->
    <?php if (isset($_POST['simpan'])): ?>
        <script>
            <?php if ($insert): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil ditambahkan.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location = 'kelola_kategori_buku.php';
                });
            <?php else: ?>
                Swal.fire('Gagal', 'Terjadi kesalahan saat menambahkan.', 'error');
            <?php endif; ?>
        </script>
    <?php endif; ?>
    </body>

</html>