<?php
include '../../includes/db.php';
$users = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="p-8 bg-gray-100">
    <?php include"../../asset/sidebar.php"; ?>
    <h1 class="text-2xl font-bold mb-4">Daftar User</h1>
    <a href="tambah_user.php" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">+ Tambah User</a>
    <table class="table-auto border w-full bg-white shadow-md rounded">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-2">No</th>
                <th class="p-2">Username</th>
                <th class="p-2">Email</th>
                <th class="p-2">Nomor Telephone</th>
                <th class="p-2">Role</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($users as $u): ?>
                <tr class="border-t">
                    <td class="p-2"><?= $no++ ?></td>
                    <td class="p-2"><?= $u['username'] ?></td>
                    <td class="p-2"><?= $u['email'] ?></td>
                    <td class="p-2"><?= $u['nomor_telephone'] ?></td>
                    <td class="p-2"><?= $u['role'] ?></td>

                    <td class="p-2">
                        <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm btn-hapus" data-id="<?= $row['id'] ?>"
                            data-nama="<?= $row['nama'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>