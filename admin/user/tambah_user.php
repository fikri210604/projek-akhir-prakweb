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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <!-- HTML Form -->
    <div class="container mt-4">
        <div class="container p-4 bg-light rounded shadow-lg mb-4">
            <h2>Tambah User</h2>
        </div>
        <div class="container p-4 bg-light rounded shadow-lg">
            <form method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Nama</label>
                        <input type="text" name="nama" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Role</label>
                        <select name="role" required class="form-control">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control">
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
        $email = $_POST['email'];
        $nama = $_POST['nama'];
        $nohp = $_POST['nomor_telepon'];
        $role = $_POST['role'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $insert = mysqli_query($conn, "INSERT INTO users (email, nama, nomor_telepon, role, password) VALUES ('$email', '$nama', '$nohp', '$role', '$password')");

        if ($insert) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data berhasil ditambahkan.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location = 'data_user.php';
            });
        </script>";
        } else {
            echo "<script>
            Swal.fire('Gagal', 'Terjadi kesalahan saat menambahkan.', 'error');
        </script>";
        }
    }
    ?>

</body>

</html>