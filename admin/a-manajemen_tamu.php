<?php
session_start();
require_once "../config/database.php";

// Cek Login Admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Logic Hapus Akun
if (isset($_GET['hapus_id'])) {
    $id_hapus = $_GET['hapus_id'];
    try {
        // QUERY: Menghapus Akun
        $sql_del = "DELETE FROM tamu WHERE no_identitas = :id";
        $stmt = $connect->prepare($sql_del);
        $stmt->execute([':id' => $id_hapus]);
        echo "<script>alert('Tamu dihapus!'); window.location='manajemen_tamu.php';</script>";
    } catch (PDOException $e) {
        echo "Gagal hapus (Mungkin tamu masih punya reservasi aktif): " . $e->getMessage();
    }
}

// QUERY: Manajemen Akun (Select)
// Kita tambah no_identitas di SELECT biar bisa dihapus berdasarkan ID
$sql = "SELECT no_identitas, nama_tamu, email, no_telepon FROM tamu";
$stmt = $connect->prepare($sql);
$stmt->execute();
$daftar_tamu = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manajemen Tamu</title>
</head>

<body>
    <h2>👥 Manajemen Akun Tamu</h2>
    <a href="dashboard.php">Kembali ke Dashboard</a>
    <hr>
    <table border="1" cellpadding="5">
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telepon</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($daftar_tamu as $tamu): ?>
            <tr>
                <td><?= $tamu['nama_tamu'] ?></td>
                <td><?= $tamu['email'] ?></td>
                <td><?= $tamu['no_telepon'] ?></td>
                <td>
                    <a href="?hapus_id=<?= $tamu['no_identitas'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>