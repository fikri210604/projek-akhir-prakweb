<?php
include '../../includes/db.php';
?>

<!-- HTML Form -->
<div class="container mt-4">
    <h2>Tambah User</h2>
    <form method="post">
        <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="mb-2">
            <label>Nama</label>
            <input type="text" name="nama" required class="form-control">
        </div>
        <div class="mb-2">
            <label>Nomor Telepon</label>
            <input type="text" name="nomor_telepon" required class="form-control">
        </div>
        <div class="mb-2">
            <label>Role</label>
            <select name="role" required class="form-control">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="mb-2">
            <label>Password</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        <a href="data_user.php" class="btn btn-secondary">Kembali</a>
    </form>
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
