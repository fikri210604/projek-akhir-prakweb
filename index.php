<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPENKU - Aplikasi Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>

<body class="bg-gradient-to-r from-blue-100 to-green-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-10 rounded-xl shadow-2xl w-full max-w-md text-center">
        <div class="flex justify-center">
            <img class="w-48 h-48" src="uploads/logo.png" alt="Logo SIMPENKU">
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang di <span class="text-blue-600">SIMPENKU</span></h1>
        <p class="text-gray-600 mb-6 text-sm">Kelola koleksi buku dan data anggota perpustakaan Anda secara digital dengan mudah dan Efisien</p>

        <div class="flex justify-center gap-4">
            <a href="login.php"
               class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition duration-300 shadow">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
            <a href="register.php"
               class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-6 rounded-lg transition duration-300 shadow">
                <i class="bi bi-person-plus"></i> Register
            </a>
        </div>
    </div>
</body>
</html>
