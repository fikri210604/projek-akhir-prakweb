<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-r from-blue-100 to-green-100 flex items-center justify-center min-h-screen">
    <div class="container mx-auto my-10 bg-blue-200 rounded-lg shadow-sm p-6 outline outline-1 outline-green-500">
        <div class="flex flex-col md:flex-row items-center justify-center gap-10">
            <!-- Gambar -->
            <div class="w-full md:w-1/2 flex justify-center">
                <img src="uploads/background.png" alt="Ilustrasi" class="w-80 md:w-full max-w-md object-contain">
            </div>

            <!-- Form Login -->
            <div class="w-full md:w-1/2 max-w-md">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Masuk</h1>
                <p class="text-gray-600 mb-4 text-sm">
                    Silakan masukkan nama lengkap dan password sesuai email yang telah dikirimkan.
                </p>

                <!-- Alert Error -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="includes/auth/proses_login.php" method="POST" class="flex flex-col gap-4">
                    <input type="text" name="nama" placeholder="Masukkan Nama Lengkap"
                        class="px-4 py-3 border border-rose-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-300"
                        required>

                    <input type="password" name="password" placeholder="Masukkan Password"
                        class="px-4 py-3 border border-rose-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-300"
                        required>

                    <button type="submit"
                        class="bg-blue-500 text-white rounded-full py-3 font-semibold hover:bg-blue-600 transition duration-300">
                        Masuk
                    </button>
                </form>

                <p class="text-sm mt-4 text-blue-400">
                    Belum punya akun?
                    <a href="register.php" class="text-blue-700 hover:underline font-bold">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>