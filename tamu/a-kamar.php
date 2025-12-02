<?php
session_start();
require_once "../config/database.php";

// Cek Login
if (!isset($_SESSION['user_login'])) {
    header("Location: ../auth/login.php");
    exit;
}
$tamu = $_SESSION['user_login'];

// Ambil kamar kosong
$sql = "SELECT * FROM kamar WHERE status = 'belum_dipesan'";
$stmt = $connect->prepare($sql);
$stmt->execute();
$kamar_tersedia = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pilih Kamar</title>
</head>

<body class="bg-gray-100 font-sans">

    <nav class="bg-gray-900 text-white p-6 flex justify-between items-center shadow-lg sticky top-0 z-50">
        <div class="text-xl font-bold text-yellow-500">🏨 LUXURY HOTEL</div>
        <div class="flex gap-4 items-center">
            <span>Halo, <?= htmlspecialchars($tamu['nama_tamu']) ?></span>
            <a href="c-profile.php" class="hover:text-yellow-400">Profil</a>
            <a href="../auth/logout.php"
                class="bg-red-600 px-4 py-2 rounded-full text-xs font-bold hover:bg-red-700 transition">Logout</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Kamar Tersedia</h2>

        <?php if (count($kamar_tersedia) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($kamar_tersedia as $row): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:-translate-y-1 transition duration-300">
                        <div class="h-40 bg-gray-800 flex items-center justify-center">
                            <span class="text-4xl">🛏️</span>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800">Kamar No. <?= $row['nomor_kamar'] ?></h3>
                            <p class="text-gray-500 text-sm mb-4">Tipe: <?= $row['tipe_kamar'] ?></p>

                            <div class="flex justify-between items-end border-t pt-4">
                                <div>
                                    <p class="text-xs text-gray-400">Harga</p>
                                    <p class="text-lg font-bold text-green-600">Rp 800.000</p>
                                </div>

                                <form action="b-pembayaran.php" method="POST">
                                    <input type="hidden" name="id_kamar" value="<?= $row['nomor_kamar'] ?>">
                                    <button type="submit"
                                        class="bg-yellow-500 hover:bg-yellow-400 text-black px-4 py-2 rounded-lg font-bold shadow-md transition">
                                        Pesan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500 py-10">Kamar penuh.</p>
        <?php endif; ?>
    </div>

</body>

</html>