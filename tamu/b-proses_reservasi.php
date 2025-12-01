<?php
session_start();
require_once "../config/database.php";

if (isset($_POST['booking']) && isset($_SESSION['user_login'])) {
    $id_kamar = $_POST['id_kamar'];
    $no_identitas = $_SESSION['user_login']['no_identitas']; // Ambil dari session login

    try {
        $connect->beginTransaction(); // Mulai Transaksi biar aman

        // 1. QUERY: Memesan Kamar (Insert Reservasi)
        // Sesuai Laporan: VALUES (id, id_kamar, no_identitas, NULL, NULL)
        // Kita biarkan check_in/out NULL dulu sesuai instruksi laporan
        $sql_reservasi = "INSERT INTO reservasi (id_kamar, no_identitas, tgl_check_in, tgl_check_out) 
                          VALUES (:id_kamar, :no_identitas, NULL, NULL)";
        $stmt1 = $connect->prepare($sql_reservasi);
        $stmt1->execute([
            ':id_kamar' => $id_kamar,
            ':no_identitas' => $no_identitas
        ]);

        // Ambil ID Reservasi yang baru dibuat untuk tabel pembayaran
        $id_reservasi_baru = $connect->lastInsertId();

        // 2. QUERY: Update Status Kamar
        // Sesuai Laporan: UPDATE kamar SET status = 'dipesan' WHERE nomor_kamar = ...
        $sql_update = "UPDATE kamar SET status = 'dipesan' WHERE nomor_kamar = :id_kamar";
        $stmt2 = $connect->prepare($sql_update);
        $stmt2->execute([':id_kamar' => $id_kamar]);

        // 3. QUERY: Pembayaran
        // Sesuai Laporan: INSERT INTO pembayaran ... (Contoh hardcode 800000 & cashless)
        $sql_bayar = "INSERT INTO pembayaran (id_reservasi, metode_pembayaran, total_bayar) 
                      VALUES (:id_res, 'cashless', 800000)";
        $stmt3 = $connect->prepare($sql_bayar);
        $stmt3->execute([':id_res' => $id_reservasi_baru]);

        $connect->commit(); // Simpan perubahan

        echo "<script>alert('Berhasil Booking & Bayar!'); window.location='kamar.php';</script>";

    } catch (Exception $e) {
        $connect->rollBack(); // Batalkan jika ada error
        echo "Gagal Reservasi: " . $e->getMessage();
    }
} else {
    header("Location: kamar.php");
}
?>