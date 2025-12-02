<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../auth/login.php");
    exit;
}

// LOGIKA CHECK-IN (Sesuai Laporan)
if (isset($_POST['check_in'])) {
    $id_res = $_POST['id_reservasi'];
    $id_kamar = $_POST['id_kamar'];

    // Update reservasi (tgl_check_in = NOW)
    $stmt1 = $connect->prepare("UPDATE reservasi SET tgl_check_in = NOW() WHERE id_reservasi = :id");
    $stmt1->execute([':id' => $id_res]);

    // Update kamar (status = 'ditempati')
    $stmt2 = $connect->prepare("UPDATE kamar SET status = 'ditempati' WHERE nomor_kamar = :kamar");
    $stmt2->execute([':kamar' => $id_kamar]);

    echo "<script>alert('✅ Berhasil Check-In!'); window.location='b-kelola_reservasi.php';</script>";
}

// LOGIKA CHECK-OUT (Sesuai Laporan)
if (isset($_POST['check_out'])) {
    $id_res = $_POST['id_reservasi'];
    $id_kamar = $_POST['id_kamar'];

    // Update reservasi (tgl_check_out = NOW)
    $stmt1 = $connect->prepare("UPDATE reservasi SET tgl_check_out = NOW() WHERE id_reservasi = :id");
    $stmt1->execute([':id' => $id_res]);

    // Update kamar (status = 'belum_dipesan') - Kamar jadi kosong lagi
    $stmt2 = $connect->prepare("UPDATE kamar SET status = 'belum_dipesan' WHERE nomor_kamar = :kamar");
    $stmt2->execute([':kamar' => $id_kamar]);

    echo "<script>alert('✅ Berhasil Check-Out!'); window.location='b-kelola_reservasi.php';</script>";
}

// Ambil Data Reservasi
$sql = "SELECT r.*, t.nama_tamu, k.status as status_kamar 
        FROM reservasi r 
        JOIN tamu t ON r.no_identitas = t.no_identitas
        JOIN kamar k ON r.id_kamar = k.nomor_kamar
        ORDER BY r.id_reservasi DESC";
$data_reservasi = $connect->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Kelola Reservasi</title>
</head>

<body class="bg-gray-100 p-8 font-sans">

    <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">📅 Kelola Check-In / Check-Out</h2>
            <a href="dashboard.php"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">Kembali</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase">
                        <th class="p-4 border-b">ID</th>
                        <th class="p-4 border-b">Tamu</th>
                        <th class="p-4 border-b">Kamar</th>
                        <th class="p-4 border-b">Status Kamar</th>
                        <th class="p-4 border-b">Check-In</th>
                        <th class="p-4 border-b">Check-Out</th>
                        <th class="p-4 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    <?php foreach ($data_reservasi as $row): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-mono">#<?= $row['id_reservasi'] ?></td>
                            <td class="p-4 font-bold"><?= htmlspecialchars($row['nama_tamu']) ?></td>
                            <td class="p-4">No. <?= $row['id_kamar'] ?></td>
                            <td class="p-4">
                                <?php
                                $status = $row['status_kamar'];
                                $color = ($status == 'dipesan') ? 'bg-yellow-100 text-yellow-800' :
                                    (($status == 'ditempati') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800');
                                ?>
                                <span
                                    class="<?= $color ?> px-2 py-1 rounded-full text-xs font-bold uppercase"><?= $status ?></span>
                            </td>
                            <td class="p-4"><?= $row['tgl_check_in'] ?? '-' ?></td>
                            <td class="p-4"><?= $row['tgl_check_out'] ?? '-' ?></td>
                            <td class="p-4">
                                <?php if ($row['status_kamar'] == 'dipesan'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi'] ?>">
                                        <input type="hidden" name="id_kamar" value="<?= $row['id_kamar'] ?>">
                                        <button type="submit" name="check_in"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded font-bold shadow">
                                            Masuk (Check-In)
                                        </button>
                                    </form>

                                <?php elseif ($row['status_kamar'] == 'ditempati'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi'] ?>">
                                        <input type="hidden" name="id_kamar" value="<?= $row['id_kamar'] ?>">
                                        <button type="submit" name="check_out"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded font-bold shadow">
                                            Keluar (Check-Out)
                                        </button>
                                    </form>

                                <?php else: ?>
                                    <span class="text-gray-400 italic">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>