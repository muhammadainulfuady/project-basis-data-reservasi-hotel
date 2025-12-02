<?php
session_start();
require_once "../config/database.php";

// Cek Login Admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 1. Logic Hapus Akun (DIPERBAIKI: Hapus Reservasi Dulu)
if (isset($_GET['hapus_id'])) {
    $id_hapus = $_GET['hapus_id'];

    try {
        // Mulai Transaksi agar aman (semua terhapus atau tidak sama sekali)
        $connect->beginTransaction();

        // LANGKAH 1: Ambil ID Reservasi milik tamu ini (untuk hapus pembayaran jika perlu)
        // Jika tabel pembayaran nge-link ke reservasi, kita harus hapus pembayaran dulu (opsional, tergantung struktur DB)
        // Untuk aman, kita coba hapus pembayaran yang terkait reservasi tamu ini
        $sql_get_res = "SELECT id_reservasi FROM reservasi WHERE no_identitas = :id";
        $stmt_get = $connect->prepare($sql_get_res);
        $stmt_get->execute([':id' => $id_hapus]);
        $reservasi_ids = $stmt_get->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($reservasi_ids)) {
            // Ubah array ID menjadi string comma-separated untuk query IN
            $placeholders = implode(',', array_fill(0, count($reservasi_ids), '?'));

            // Hapus Pembayaran terkait
            $stmt_del_pay = $connect->prepare("DELETE FROM pembayaran WHERE id_reservasi IN ($placeholders)");
            $stmt_del_pay->execute($reservasi_ids);
        }

        // LANGKAH 2: Hapus Data di Tabel Reservasi milik tamu ini
        $stmt_res = $connect->prepare("DELETE FROM reservasi WHERE no_identitas = :id");
        $stmt_res->execute([':id' => $id_hapus]);

        // LANGKAH 3: Baru Hapus Akun Tamu
        $stmt_tamu = $connect->prepare("DELETE FROM tamu WHERE no_identitas = :id");
        $stmt_tamu->execute([':id' => $id_hapus]);

        // Simpan perubahan permanen
        $connect->commit();

        echo "<script>alert('Data tamu beserta riwayat reservasinya berhasil dihapus!'); window.location='a-manajemen_tamu.php';</script>";

    } catch (PDOException $e) {
        // Batalkan jika ada error
        $connect->rollBack();
        echo "<script>alert('Gagal menghapus: " . $e->getMessage() . "'); window.location='a-manajemen_tamu.php';</script>";
    }
}

// 2. [BARU] Logic Reset Password
if (isset($_POST['reset_id'])) {
    $id_reset = $_POST['reset_id'];
    $pass_default = '12345'; // Password default baru

    try {
        $stmt = $connect->prepare("UPDATE tamu SET password = :pass WHERE no_identitas = :id");
        $stmt->execute([':pass' => $pass_default, ':id' => $id_reset]);
        echo "<script>alert('Password berhasil direset menjadi: 12345'); window.location='a-manajemen_tamu.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error reset password: " . $e->getMessage() . "');</script>";
    }
}

// Ambil Data Tamu
$sql = "SELECT no_identitas, nama_tamu, email, no_telepon FROM tamu";
$daftar_tamu = $connect->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manajemen Tamu</title>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <nav class="bg-red-900 text-white p-6 shadow-md flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2"><span class="text-2xl">👥</span>
            <h1 class="text-xl font-bold">Manajemen Tamu</h1>
        </div>
        <a href="dashboard.php" class="text-gray-200 hover:text-white text-sm">← Kembali ke Dashboard</a>
    </nav>

    <div class="max-w-6xl mx-auto p-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-4 border-b">No. Identitas</th>
                        <th class="p-4 border-b">Nama Lengkap</th>
                        <th class="p-4 border-b">Kontak</th>
                        <th class="p-4 border-b text-center">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach ($daftar_tamu as $tamu): ?>
                        <tr class="hover:bg-red-50">
                            <td class="p-4 font-mono text-gray-500"><?= htmlspecialchars($tamu['no_identitas']) ?></td>
                            <td class="p-4 font-bold"><?= htmlspecialchars($tamu['nama_tamu']) ?></td>
                            <td class="p-4">
                                <div class="text-xs text-gray-500">Email: <?= htmlspecialchars($tamu['email']) ?></div>
                                <div class="text-xs text-gray-500">Telp: <?= htmlspecialchars($tamu['no_telepon']) ?></div>
                            </td>
                            <td class="p-4 flex justify-center gap-2">
                                <form method="POST"
                                    onsubmit="return confirm('Reset password <?= $tamu['nama_tamu'] ?> menjadi 12345?')">
                                    <input type="hidden" name="reset_id" value="<?= $tamu['no_identitas'] ?>">
                                    <button type="submit"
                                        class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg border border-yellow-300 hover:bg-yellow-200 text-xs font-bold">
                                        🔑 Reset Pass
                                    </button>
                                </form>

                                <a href="?hapus_id=<?= $tamu['no_identitas'] ?>"
                                    onclick="return confirm('Yakin hapus akun ini?')"
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded-lg border border-red-300 hover:bg-red-200 text-xs font-bold">
                                    🗑️ Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>