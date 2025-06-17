<?php
session_start();
include '../../includes/db.php';
include '../asset/navbar.php';

if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun_terbit'];
    $kategori = $_POST['kategori_id'];
    $jumlah = $_POST['jumlah'];
    $status = $_POST['status'];

    // Menghandle Upload Foto
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $folder = "../../uploads/buku/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $path_simpan = $folder . $foto;
    move_uploaded_file($tmp, $path_simpan);

    $insert = mysqli_query($conn, "INSERT INTO buku 
        (judul, penulis, penerbit, tahun_terbit, kategori_id, jumlah, status, foto) 
        VALUES ('$judul', '$penulis', '$penerbit', '$tahun', '$kategori', '$jumlah', '$status', '$foto')");

    if ($insert) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data buku berhasil ditambahkan.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = 'kelola_buku.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire('Gagal', 'Terjadi kesalahan saat menambahkan.', 'error');
        </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tambah Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>

<body>
    <div class="container mt-4">
        <h2>Tambah Buku</h2>
        <div class="bg-light p-4 rounded shadow">
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-book"></i> Judul Buku</label>
                        <input type="text" name="judul" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-person"></i> Penulis</label>
                        <input type="text" name="penulis" required class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-building"></i> Penerbit</label>
                        <input type="text" name="penerbit" required class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-calendar"></i> Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" required class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-tags"></i> Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                            $kategori = mysqli_query($conn, "SELECT * FROM kategori");
                            while ($row = mysqli_fetch_assoc($kategori)) {
                                echo "<option value='{$row['id']}'>{$row['nama_kategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-hash"></i> Jumlah</label>
                        <input type="number" name="jumlah" required class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-check-circle"></i> Status</label>
                        <select name="status" class="form-select" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam">Dipinjam</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="bi bi-image"></i> Upload Foto Buku</label>
                        <input type="file" name="foto" accept="image/*" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="kelola_buku.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                    <button type="submit" name="simpan" class="btn btn-success"><i class="bi bi-save"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>