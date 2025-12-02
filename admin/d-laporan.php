<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 1. Hitung Total Pendapatan
$sql_uang = "SELECT SUM(total_bayar) as total FROM pembayaran";
$stmt = $connect->query($sql_uang);
$pendapatan = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// 2. Hitung Total Reservasi
$sql_res = "SELECT COUNT(*) as total FROM reservasi";
$reservasi = $connect->query($sql_res)->fetch(PDO::FETCH_ASSOC)['total'];

// 3. Hitung Kamar Terisi
$sql_isi = "SELECT COUNT(*) as total FROM kamar WHERE status = 'ditempati' OR status = 'dipesan'";
$terisi = $connect->query($sql_isi)->fetch(PDO::FETCH_ASSOC)['total'];

// 4. Data Transaksi Terakhir
$sql_list = "SELECT p.*, t.nama_tamu, r.tgl_check_in 
             FROM pembayaran p
             JOIN reservasi r ON p.id_reservasi = r.id_reservasi
             JOIN tamu t ON r.no_identitas = t.no_identitas
             ORDER BY p.id_pembayaran DESC LIMIT 5";
$transaksi = $connect->query($sql_list)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Laporan Keuangan</title>
</head>

<body class="bg-gray-100 font-sans">

    <nav class="bg-red-900 text-white p-6 shadow-md flex justify-between items-center">
        <div class="flex items-center gap-2"><span class="text-2xl">📊</span>
            <h1 class="text-xl font-bold">Laporan Pendapatan</h1>
        </div>
        <a href="dashboard.php" class="text-gray-200 hover:text-white text-sm">← Kembali ke Dashboard</a>
    </nav>

    <div class="max-w-6xl mx-auto p-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-green-500">
                <p class="text-gray-500 text-xs uppercase font-bold">Total Pendapatan</p>
                <h3 class="text-3xl font-bold text-green-600">Rp <?= number_format($pendapatan, 0, ',', '.') ?></h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-blue-500">
                <p class="text-gray-500 text-xs uppercase font-bold">Total Transaksi Reservasi</p>
                <h3 class="text-3xl font-bold text-blue-600"><?= $reservasi ?> <span
                        class="text-sm text-gray-400 font-normal">Kali</span></h3>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-yellow-500">
                <p class="text-gray-500 text-xs uppercase font-bold">Kamar Sedang Terisi</p>
                <h3 class="text-3xl font-bold text-yellow-600"><?= $terisi ?> <span
                        class="text-sm text-gray-400 font-normal">Kamar</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b">
                <h3 class="font-bold text-gray-700">Transaksi Terbaru</h3>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="p-4">ID Bayar</th>
                        <th class="p-4">Nama Tamu</th>
                        <th class="p-4">Metode</th>
                        <th class="p-4">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach ($transaksi as $row): ?>
                        <tr>
                            <td class="p-4 font-mono">#<?= $row['id_pembayaran'] ?></td>
                            <td class="p-4 font-bold"><?= htmlspecialchars($row['nama_tamu']) ?></td>
                            <td class="p-4 uppercase text-xs font-bold text-gray-500"><?= $row['metode_pembayaran'] ?></td>
                            <td class="p-4 font-bold text-green-600">+ Rp <?= number_format($row['total_bayar']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($transaksi)): ?>
                <p class="p-6 text-center text-gray-400 italic">Belum ada data transaksi.</p>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>