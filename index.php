<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Luxury Hotel - Welcome</title>
</head>

<body class="bg-gray-900 font-sans text-white">

    <nav class="absolute top-0 w-full p-6 flex justify-between items-center z-20">
        <div class="text-2xl font-bold text-yellow-500 tracking-widest">
            🏨 LUXURY HOTEL
        </div>
        <div>
            <?php if (isset($_SESSION['user_login'])): ?>
                <span class="mr-4 text-gray-300 text-sm">Halo,
                    <b><?= htmlspecialchars($_SESSION['user_login']['nama_tamu']) ?></b></span>
                <a href="tamu/a-kamar.php"
                    class="text-yellow-400 hover:text-white mr-4 font-semibold text-sm transition">Dashboard</a>
                <a href="auth/logout.php"
                    class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded-full font-bold text-xs transition shadow-lg">
                    Logout
                </a>
            <?php elseif (isset($_SESSION['admin_logged_in'])): ?>
                <span class="mr-4 text-gray-300 text-sm">Halo, <b>Admin</b></span>
                <a href="admin/dashboard.php"
                    class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded-full font-bold text-xs transition shadow-lg">
                    Panel Admin
                </a>
            <?php else: ?>
                <a href="auth/login.php" class="text-white hover:text-yellow-400 mr-6 font-semibold transition text-sm">
                    Masuk
                </a>
                <a href="auth/registrasi.php"
                    class="bg-yellow-500 hover:bg-yellow-400 text-black px-6 py-2 rounded-full font-bold text-sm transition shadow-lg hover:shadow-yellow-500/50">
                    Daftar Sekarang
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="relative h-screen flex items-center justify-center bg-cover bg-center"
        style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), url('images/image.png');">

        <div class="text-center max-w-4xl px-6 relative z-10 animate-fade-in-up">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight tracking-tight">
                Nikmati Kenyamanan <br> <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-600">Kelas
                    Dunia</span>
            </h1>
            <p class="text-gray-300 text-lg md:text-xl mb-10 font-light max-w-2xl mx-auto">
                Sistem reservasi hotel termudah dan tercepat. Rasakan pengalaman menginap tak terlupakan dengan
                pelayanan bintang lima.
            </p>

            <?php if (isset($_SESSION['user_login'])): ?>
                <div class="flex justify-center gap-6">
                    <a href="tamu/a-kamar.php"
                        class="bg-yellow-500 hover:bg-yellow-400 text-black text-lg px-8 py-4 rounded-full font-bold shadow-lg shadow-yellow-500/30 transform hover:scale-105 transition duration-300">
                        🛏️ Pesan Kamar
                    </a>
                    <a href="tamu/c-profile.php"
                        class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white text-lg px-8 py-4 rounded-full font-bold border border-white/20 shadow-lg transition duration-300">
                        👤 Profil Saya
                    </a>
                </div>
            <?php else: ?>
                <a href="auth/login.php"
                    class="inline-block bg-yellow-500 hover:bg-yellow-400 text-black text-lg px-10 py-4 rounded-full font-bold shadow-xl shadow-yellow-500/30 transform hover:scale-105 transition duration-300">
                    Mulai Menginap Sekarang
                </a>
            <?php endif; ?>
        </div>

        <div class="absolute bottom-10 animate-bounce">
            <span class="text-gray-400 text-2xl">↓</span>
        </div>
    </div>

    <footer class="bg-black text-center py-8 text-gray-600 text-xs border-t border-gray-900">
        &copy; 2025 Luxury Hotel. Kelompok 7 Basis Data.
    </footer>

</body>

</html>