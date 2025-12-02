<?php
session_start();
require_once "../config/database.php";

// Cek akses: harus login dan ada data kamar yang dikirim
if (!isset($_SESSION['user_login']) || !isset($_POST['id_kamar'])) {
    header("Location: a-kamar.php");
    exit;
}

$id_kamar = $_POST['id_kamar'];
$tamu = $_SESSION['user_login'];
$harga_fix = 800000; // Hardcode sesuai laporan
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pembayaran - Luxury Hotel</title>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans p-4">

    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg border border-gray-200">

        <div class="text-center border-b pb-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">💳 Konfirmasi Pembayaran</h2>
            <p class="text-gray-500 text-sm">Selesaikan pembayaran untuk reservasi Anda</p>
        </div>

        <div class="space-y-4 mb-8">
            <div class="flex justify-between">
                <span class="text-gray-600">Nama Tamu</span>
                <span class="font-bold text-gray-800"><?= htmlspecialchars($tamu['nama_tamu']) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Nomor Kamar</span>
                <span class="font-bold text-gray-800">No. <?= $id_kamar ?></span>
            </div>
            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg">
                <span class="text-gray-600">Total Biaya</span>
                <span class="text-xl font-bold text-green-600">Rp <?= number_format($harga_fix, 0, ',', '.') ?></span>
            </div>
        </div>

        <form action="b-proses_reservasi.php" method="POST">
            <input type="hidden" name="id_kamar" value="<?= $id_kamar ?>">
            <input type="hidden" name="total_bayar" value="<?= $harga_fix ?>">

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Metode Pembayaran</label>
                <select name="metode_pembayaran" required
                    class="w-full p-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-yellow-500 outline-none">
                    <option value="" disabled selected>-- Pilih Metode --</option>
                    <option value="cashless">Cashless (QRIS / E-Wallet)</option>
                    <option value="transfer_bank">Transfer Bank (BCA/Mandiri)</option>
                    <option value="kartu_kredit">Kartu Kredit / Debit</option>
                </select>
            </div>

            <div class="flex gap-3">
                <a href="a-kamar.php"
                    class="w-1/2 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-center hover:bg-gray-300 transition">
                    Batal
                </a>
                <button type="submit" name="bayar"
                    class="w-1/2 bg-yellow-500 text-black font-bold py-3 rounded-xl hover:bg-yellow-400 transition shadow-lg">
                    Bayar Sekarang
                </button>
            </div>
        </form>

    </div>

</body>

</html>