<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['admin_logged_in']))
    exit("Akses Ditolak");

// LOGIK CHECK-IN
if (isset($_POST['check_in'])) {
    $id_res = $_POST['id_reservasi'];
    $id_kamar = $_POST['id_kamar']; // Dikirim dari form hidden

    // 1. Update Reservasi (tgl_check_in = NOW)
    $sql1 = "UPDATE reservasi SET tgl_check_in = NOW() WHERE id_reservasi = :id";
    $stmt1 = $connect->prepare($sql1);
    $stmt1->execute([':id' => $id_res]);

    // 2. Update Kamar (status = 'ditempati')
    $sql2 = "UPDATE kamar SET status = 'ditempati' WHERE nomor_kamar = :kamar";
    $stmt2 = $connect->prepare($sql2);
    $stmt2->execute([':kamar' => $id_kamar]);

    echo "<script>alert('Check-In Berhasil!');</script>";
}

// LOGIK CHECK-OUT
if (isset($_POST['check_out'])) {
    $id_res = $_POST['id_reservasi'];
    $id_kamar = $_POST['id_kamar'];

    // 1. Update Reservasi (tgl_check_out = NOW)
    $sql1 = "UPDATE reservasi SET tgl_check_out = NOW() WHERE id_reservasi = :id";
    $stmt1 = $connect->prepare($sql1);
    $stmt1->execute([':id' => $id_res]);

    // 2. Update Kamar (status = 'belum_dipesan')
    $sql2 = "UPDATE kamar SET status = 'belum_dipesan' WHERE nomor_kamar = :kamar";
    $stmt2 = $connect->prepare($sql2);
    $stmt2->execute([':kamar' => $id_kamar]);

    echo "<script>alert('Check-Out Berhasil! Kamar kosong kembali.');</script>";
}

// Ambil Data Reservasi untuk ditampilkan
$sql_show = "SELECT r.*, t.nama_tamu, k.status as status_kamar 
             FROM reservasi r 
             JOIN tamu t ON r.no_identitas = t.no_identitas
             JOIN kamar k ON r.id_kamar = k.nomor_kamar";
$data_reservasi = $connect->query($sql_show)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Kelola Reservasi</title>
</head>

<body>
    <h2>📅 Kelola Check-In / Check-Out</h2>
    <a href="dashboard.php">Kembali</a>
    <table border="1" cellpadding="5" style="margin-top:20px; width:100%">
        <tr>
            <th>ID Res</th>
            <th>Tamu</th>
            <th>Kamar</th>
            <th>Status Kamar</th>
            <th>Check In (Waktu)</th>
            <th>Check Out (Waktu)</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($data_reservasi as $row): ?>
            <tr>
                <td><?= $row['id_reservasi'] ?></td>
                <td><?= $row['nama_tamu'] ?></td>
                <td><?= $row['id_kamar'] ?></td>
                <td><?= $row['status_kamar'] ?></td>
                <td><?= $row['tgl_check_in'] ?? '-' ?></td>
                <td><?= $row['tgl_check_out'] ?? '-' ?></td>
                <td>
                    <?php if ($row['status_kamar'] == 'dipesan'): ?>
                        <form method="POST">
                            <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi'] ?>">
                            <input type="hidden" name="id_kamar" value="<?= $row['id_kamar'] ?>">
                            <button type="submit" name="check_in" style="background:lightgreen">Check In</button>
                        </form>

                    <?php elseif ($row['status_kamar'] == 'ditempati'): ?>
                        <form method="POST">
                            <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi'] ?>">
                            <input type="hidden" name="id_kamar" value="<?= $row['id_kamar'] ?>">
                            <button type="submit" name="check_out" style="background:salmon">Check Out</button>
                        </form>

                    <?php else: ?>
                        Selesai / Kosong
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>