<?php
session_start();
// Cek Login Admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard Admin</title>
</head>

<body class="bg-gray-50 font-sans">

    <nav class="bg-red-900 text-white p-6 shadow-md flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <span class="text-2xl">🛡️</span>
            <div class="font-bold text-xl tracking-wide">Admin Panel</div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-gray-200 text-sm">Halo, <b><?= htmlspecialchars($_SESSION['admin_nama']) ?></b></span>
            <a href="../auth/logout.php"
                class="bg-white text-red-900 px-4 py-2 rounded-lg font-bold text-xs hover:bg-gray-100 transition shadow-sm">Logout</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto p-10">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-2">Dashboard Pengelola</h1>
            <p class="text-gray-500">Pilih menu untuk mengelola sistem hotel</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <a href="a-manajemen_tamu.php" class="block group h-full">
                <div
                    class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 hover:border-red-500 hover:shadow-xl transition transform hover:-translate-y-2 h-full flex flex-col items-center text-center">
                    <div
                        class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-2xl mb-4">
                        👥</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Manajemen Tamu</h3>
                    <p class="text-gray-500 text-xs">Reset password & hapus akun.</p>
                </div>
            </a>

            <a href="c-kelola_kamar.php" class="block group h-full">
                <div
                    class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 hover:border-yellow-500 hover:shadow-xl transition transform hover:-translate-y-2 h-full flex flex-col items-center text-center">
                    <div
                        class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-2xl mb-4">
                        🛏️</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Kelola Kamar</h3>
                    <p class="text-gray-500 text-xs">Tambah, edit, & hapus kamar.</p>
                </div>
            </a>

            <!-- <a href="d-laporan.php" class="block group h-full">
                <div
                    class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 hover:border-yellow-500 hover:shadow-xl transition transform hover:-translate-y-2 h-full flex flex-col items-center text-center">
                    <div
                        class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-2xl mb-4">
                        📶</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Laporan Pendapatann</h3>
                    <p class="text-gray-500 text-xs">Lihat Pendapatann.</p>
                </div>
            </a> -->

            <a href="b-kelola_reservasi.php" class="block group h-full">
                <div
                    class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 hover:border-blue-500 hover:shadow-xl transition transform hover:-translate-y-2 h-full flex flex-col items-center text-center">
                    <div
                        class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl mb-4">
                        📅</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Kelola Reservasi</h3>
                    <p class="text-gray-500 text-xs">Check-In & Check-Out tamu.</p>
                </div>
            </a>

            <a href="registrasi_admin.php" class="block group h-full">
                <div
                    class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 hover:border-green-500 hover:shadow-xl transition transform hover:-translate-y-2 h-full flex flex-col items-center text-center">
                    <div
                        class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-4">
                        🛡️</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Tambah Admin</h3>
                    <p class="text-gray-500 text-xs">Daftarkan rekan pengelola baru.</p>
                </div>
            </a>

        </div>
    </div>

    <footer class="text-center text-gray-400 text-xs py-8 mt-8 border-t">
        &copy; 2025 Luxury Hotel System. All rights reserved.
    </footer>

</body>

</html>