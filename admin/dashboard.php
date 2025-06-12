<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        <?php include 'asset/sidebar.php'; ?>

        <main class="flex-grow p-8 overflow-auto">
            <h1 class="text-3xl font-bold mb-4">Admin Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-2">Total Users</h2>
                    <p class="text-3xl"><?php echo $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count']; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-2">Total Books</h2>
                    <p class="text-3xl"><?php echo $conn->query("SELECT COUNT(*) as count FROM buku")->fetch_assoc()['count']; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-2">Total Categories</h2>
                    <p class="text-3xl"><?php echo $conn->query("SELECT COUNT(*) as count FROM kategori")->fetch_assoc()['count']; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-2">Total Loans</h2>
                    <p class="text-3xl"><?php echo $conn->query("SELECT COUNT(*) as count FROM peminjaman")->fetch_assoc()['count']; ?></p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>