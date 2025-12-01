<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Luxury Hotel - Selamat Datang</title>
</head>

<body class="bg-gray-900 min-h-screen font-sans text-white">

    <nav class="absolute top-0 w-full p-6 flex justify-between items-center z-10">
        <div class="text-2xl font-bold text-yellow-500 tracking-wider">
            🏨 LUXURY HOTEL
        </div>
        <div>
            <?php if (isset($_SESSION['user_login'])): ?>
                <span class="mr-4 text-gray-300">Halo, <b><?= $_SESSION['user_login']['nama_tamu'] ?></b></span>
                <a href="./auth/logout.php"
                    class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded-full font-semibold transition">
                    Logout
                </a>
            <?php else: ?>
                <a href="admin/login.php" class="text-gray-400 hover:text-white mr-6 text-sm font-semibold transition">
                    Akses Admin
                </a>
                <a href="auth/login.php"
                    class="bg-transparent border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-black px-5 py-2 rounded-full font-semibold transition mr-2">
                    Login
                </a>
                <a href="auth/registrasi.php"
                    class="bg-yellow-500 hover:bg-yellow-400 text-black px-5 py-2 rounded-full font-bold transition">
                    Daftar
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="relative h-screen flex items-center justify-center bg-cover bg-center"
        style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('images/image.png');">

        <div class="text-center max-w-3xl px-4">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
                Nikmati Kenyamanan <br> <span class="text-yellow-500">Kelas Dunia</span>
            </h1>
            <p class="text-gray-300 text-lg md:text-xl mb-10">
                Sistem reservasi hotel termudah dan tercepat. Pesan kamar impian Anda sekarang juga.
            </p>

            <?php if (isset($_SESSION['user_login'])): ?>
                <div class="flex justify-center gap-4 flex-wrap">
                    <a href="./tamu/a-kamar.php"
                        class="bg-yellow-500 hover:bg-yellow-400 text-black text-lg px-8 py-4 rounded-xl font-bold shadow-lg transform hover:scale-105 transition">
                        🛏️ Pesan Kamar
                    </a>
                    <a href="./tamu/c-profile.php"
                        class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white text-lg px-8 py-4 rounded-xl font-bold border border-white/30 shadow-lg transition">
                        👤 Profil Saya
                    </a>
                </div>
            <?php else: ?>
                <a href="auth/login.php"
                    class="inline-block bg-yellow-500 hover:bg-yellow-400 text-black text-lg px-10 py-4 rounded-xl font-bold shadow-lg transform hover:scale-105 transition">
                    Mulai Menginap
                </a>
            <?php endif; ?>
        </div>
    </div>

    <footer class="bg-gray-900 text-center py-6 text-gray-500 text-sm">
        &copy; 2025 Luxury Hotel. Kelompok 7 Basis Data.
    </footer>

</body>

</html>