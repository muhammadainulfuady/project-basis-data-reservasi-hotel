<?php
session_start();
require_once "../config/database.php";

// Cek Login
if (!isset($_SESSION['user_login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$tamu = $_SESSION['user_login'];
$id_tamu = $tamu['no_identitas'];
$message = "";

// ------------------------------------------
// 1. [Laporan: Update Data Tamu]
// ------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_tamu'];
    $email = $_POST['email'];
    $telp = $_POST['no_telepon'];

    try {
        // Query sesuai laporan: UPDATE tamu SET ... WHERE no_identitas = ...
        $sql_update = "UPDATE tamu SET nama_tamu = :nama, email = :email, no_telepon = :telp WHERE no_identitas = :id";
        $stmt = $connect->prepare($sql_update);
        $stmt->execute([
            ':nama' => $nama,
            ':email' => $email,
            ':telp' => $telp,
            ':id' => $id_tamu
        ]);

        // Perbarui data sesi agar tampilan langsung berubah
        $_SESSION['user_login']['nama_tamu'] = $nama;
        $_SESSION['user_login']['email'] = $email;
        $_SESSION['user_login']['no_telepon'] = $telp;
        $tamu = $_SESSION['user_login']; // Refresh variabel

        $message = "Profil berhasil diperbarui!";
    } catch (PDOException $e) {
        $message = "Gagal update: " . $e->getMessage();
    }
}

// ------------------------------------------
// 2. Ambil Riwayat Pesanan (Fitur Tambahan)
// ------------------------------------------
// Menggunakan JOIN agar profil terlihat lebih informatif
$sql_hist = "SELECT r.*, k.tipe_kamar, k.status as status_kamar, p.total_bayar 
             FROM reservasi r
             JOIN kamar k ON r.id_kamar = k.nomor_kamar
             LEFT JOIN pembayaran p ON r.id_reservasi = p.id_reservasi
             WHERE r.no_identitas = :id 
             ORDER BY r.id_reservasi DESC";
$stmt_hist = $connect->prepare($sql_hist);
$stmt_hist->execute([':id' => $id_tamu]);
$riwayat = $stmt_hist->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Profil Saya - Luxury Hotel</title>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <nav class="bg-gray-900 text-white p-6 shadow-md flex justify-between items-center sticky top-0 z-50">
        <div class="text-xl font-bold text-yellow-500">👤 PROFIL SAYA</div>
        <div class="flex gap-4 text-sm">
            <a href="a-kamar.php" class="hover:text-yellow-400 transition">← Daftar Kamar</a>
            <a href="../auth/logout.php"
                class="bg-red-600 hover:bg-red-700 px-4 py-1 rounded-full transition">Logout</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto p-8 grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 bg-gray-200 rounded-full mx-auto flex items-center justify-center text-4xl mb-3">
                        👤</div>
                    <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($tamu['nama_tamu']) ?></h2>
                    <p class="text-gray-500 text-sm">Tamu Terdaftar</p>
                </div>

                <?php if ($message): ?>
                    <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm text-center font-semibold">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_tamu" value="<?= htmlspecialchars($tamu['nama_tamu']) ?>" required
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($tamu['email']) ?>" required
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. Telepon</label>
                        <input type="text" name="no_telepon" value="<?= htmlspecialchars($tamu['no_telepon']) ?>"
                            required
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-yellow-400 outline-none bg-gray-50">
                    </div>
                    <button type="submit"
                        class="w-full bg-gray-900 hover:bg-black text-white font-bold py-2 rounded-lg transition mt-2">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 min-h-[400px]">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">📜 Riwayat Reservasi</h3>

                <?php if (count($riwayat) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wide">
                                    <th class="p-3 rounded-l-lg">Kamar</th>
                                    <th class="p-3">Tgl Check-In</th>
                                    <th class="p-3">Tgl Check-Out</th>
                                    <th class="p-3">Total</th>
                                    <th class="p-3 rounded-r-lg">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <?php foreach ($riwayat as $row): ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="p-4 font-bold text-gray-800">
                                            No. <?= $row['id_kamar'] ?>
                                            <span class="text-gray-400 font-normal text-xs ml-1">(Tipe
                                                <?= $row['tipe_kamar'] ?>)</span>
                                        </td>
                                        <td class="p-4 text-gray-600">
                                            <?= $row['tgl_check_in'] ? date('d M Y H:i', strtotime($row['tgl_check_in'])) : '-' ?>
                                        </td>
                                        <td class="p-4 text-gray-600">
                                            <?= $row['tgl_check_out'] ? date('d M Y H:i', strtotime($row['tgl_check_out'])) : '-' ?>
                                        </td>
                                        <td class="p-4 font-bold text-green-600">
                                            Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?>
                                        </td>
                                        <td class="p-4">
                                            <?php
                                            // Logika Status Sederhana untuk Tampilan
                                            if ($row['tgl_check_out']) {
                                                echo '<span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-xs font-bold">Selesai</span>';
                                            } elseif ($row['tgl_check_in']) {
                                                echo '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Sedang Menginap</span>';
                                            } else {
                                                echo '<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Booked</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-20">
                        <p class="text-6xl mb-4">🛏️</p>
                        <p class="text-gray-500 text-lg">Anda belum melakukan pemesanan.</p>
                        <a href="a-kamar.php" class="text-yellow-600 hover:underline font-bold mt-2 inline-block">Mulai
                            Pesan Kamar →</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</body>

</html>