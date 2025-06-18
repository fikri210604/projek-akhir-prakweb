<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'petugas') {
    header("Location: ../login.php");
    exit;
}

include '../../includes/db.php';
include '../asset/navbar.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penulis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">
    <div class="container mt-4">
        <div class="p-4 bg-light rounded shadow mb-4">
            <h2>Tambah Penulis</h2>
        </div>

        <div class="p-4 bg-light rounded shadow">
            <form method="post" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Nama Penulis</label>
                        <input type="text" name="nama_penulis" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Biografi</label>
                        <input type="text" name="biografi" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Kebangsaan</label>
                        <input type="text" name="kebangsaan" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="form-control">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Foto Penulis</label>
                        <input type="file" name="foto" accept="image/*" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="kelola_user.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                    <button type="submit" name="simpan" class="btn btn-success"><i class="bi bi-save"></i>
                        Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (isset($_POST['simpan'])) {
        $nama_penulis = mysqli_real_escape_string($conn, $_POST['nama_penulis']);
        $biografi = mysqli_real_escape_string($conn, $_POST['biografi']);
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $kebangsaan = mysqli_real_escape_string($conn, $_POST['kebangsaan']);
        $jenis_kelamin = $_POST['jenis_kelamin'];

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $foto_name = $_FILES['foto']['name'];
            $foto_tmp = $_FILES['foto']['tmp_name'];
            $foto_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($foto_ext, $allowed_ext)) {
                $new_foto_name = uniqid('penulis_', true) . '.' . $foto_ext;
                $upload_path = '../../uploads/penulis/' . $new_foto_name;

                if (move_uploaded_file($foto_tmp, $upload_path)) {
                    $insert = mysqli_query($conn, "INSERT INTO penulis 
                        (nama_penulis, bio, tanggal_lahir, kebangsaan, jenis_kelamin, foto) 
                        VALUES ('$nama_penulis', '$biografi', '$tanggal_lahir', '$kebangsaan', '$jenis_kelamin', '$new_foto_name')");

                    if ($insert) {
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data penulis berhasil ditambahkan.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location = 'kelola_penulis.php';
                            });
                        </script>";
                    } else {
                        echo "<script>Swal.fire('Gagal', 'Gagal menyimpan data ke database.', 'error');</script>";
                    }
                } else {
                    echo "<script>Swal.fire('Gagal', 'Upload foto gagal.', 'error');</script>";
                }
            } else {
                echo "<script>Swal.fire('Gagal', 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.', 'error');</script>";
            }
        } else {
            echo "<script>Swal.fire('Gagal', 'File foto tidak tersedia atau bermasalah.', 'error');</script>";
        }
    }
    ?>
</body>

</html>