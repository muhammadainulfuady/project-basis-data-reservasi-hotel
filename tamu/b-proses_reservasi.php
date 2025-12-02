<?php
session_start();
require_once "../config/database.php";

// Cek validasi akses dari tombol 'bayar'
if (!isset($_POST['bayar']) || !isset($_SESSION['user_login'])) {
    header("Location: a-kamar.php");
    exit;
}

// Ambil data dari form pembayaran
$id_kamar = $_POST['id_kamar'];
$no_identitas = $_SESSION['user_login']['no_identitas'];
$metode = $_POST['metode_pembayaran']; // Dinamis dari pilihan user
$total = $_POST['total_bayar']; // 800000

try {
    $connect->beginTransaction();

    // 1. [Laporan: Reservasi] 
    // Insert data reservasi
    $sql_res = "INSERT INTO reservasi (id_kamar, no_identitas, tgl_check_in, tgl_check_out) 
                VALUES (:kamar, :identitas, NULL, NULL)";
    $stmt1 = $connect->prepare($sql_res);
    $stmt1->execute([':kamar' => $id_kamar, ':identitas' => $no_identitas]);

    $id_reservasi_baru = $connect->lastInsertId();

    // 2. [Laporan: Update Kamar]
    // Ubah status kamar jadi 'dipesan'
    $sql_kamar = "UPDATE kamar SET status = 'dipesan' WHERE nomor_kamar = :kamar";
    $stmt2 = $connect->prepare($sql_kamar);
    $stmt2->execute([':kamar' => $id_kamar]);

    // 3. [Laporan: Pembayaran]
    // Insert pembayaran sesuai metode yang dipilih
    $sql_bayar = "INSERT INTO pembayaran (id_reservasi, metode_pembayaran, total_bayar) 
                  VALUES (:id_res, :metode, :total)";
    $stmt3 = $connect->prepare($sql_bayar);
    $stmt3->execute([
        ':id_res' => $id_reservasi_baru,
        ':metode' => $metode,
        ':total' => $total
    ]);

    $connect->commit();

    // TAMPILAN SUKSES
    ?>
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Transaksi Sukses</title>
    </head>

    <body class="bg-gray-900 flex items-center justify-center min-h-screen text-white font-sans">
        <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl text-center max-w-sm border border-gray-700 animate-bounce-in">
            <div
                class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-lg shadow-green-500/50">
                ✅
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-400 mb-6 text-sm">
                Terima kasih, pembayaran sebesar <b>Rp <?= number_format($total) ?></b> via
                <b><?= ucwords(str_replace('_', ' ', $metode)) ?></b> telah diterima.
            </p>
            <div class="space-y-3">
                <a href="c-profile.php"
                    class="block w-full bg-yellow-500 text-black font-bold py-3 rounded-xl hover:bg-yellow-400 transition">
                    Lihat Tiket / Profil
                </a>
                <a href="a-kamar.php" class="block text-gray-400 hover:text-white text-sm">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </body>

    </html>
    <?php
    exit;

} catch (Exception $e) {
    $connect->rollBack();
    echo "<script>alert('Gagal Transaksi: " . $e->getMessage() . "'); window.location='a-kamar.php';</script>";
}
?>