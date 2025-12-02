<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 1. TAMBAH KAMAR
if (isset($_POST['tambah_kamar'])) {
    $no_kamar = $_POST['nomor_kamar'];
    $tipe = $_POST['tipe_kamar'];

    try {
        // Default status 'belum_dipesan'
        $sql = "INSERT INTO kamar (nomor_kamar, tipe_kamar, status) VALUES (:no, :tipe, 'belum_dipesan')";
        $stmt = $connect->prepare($sql);
        $stmt->execute([':no' => $no_kamar, ':tipe' => $tipe]);
        echo "<script>alert('Kamar berhasil ditambahkan!'); window.location='c-kelola_kamar.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal: Nomor kamar mungkin sudah ada.');</script>";
    }
}

// 2. HAPUS KAMAR
if (isset($_GET['hapus_id'])) {
    try {
        $stmt = $connect->prepare("DELETE FROM kamar WHERE nomor_kamar = :id");
        $stmt->execute([':id' => $_GET['hapus_id']]);
        echo "<script>alert('Kamar dihapus!'); window.location='c-kelola_kamar.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal hapus: Kamar ini ada di riwayat reservasi.'); window.location='c-kelola_kamar.php';</script>";
    }
}

// 3. EDIT STATUS/TIPE
if (isset($_POST['update_kamar'])) {
    $no_kamar = $_POST['nomor_kamar']; // ID (Hidden)
    $tipe_baru = $_POST['tipe_kamar'];
    $status_baru = $_POST['status'];

    $stmt = $connect->prepare("UPDATE kamar SET tipe_kamar = :tipe, status = :st WHERE nomor_kamar = :no");
    $stmt->execute([':tipe' => $tipe_baru, ':st' => $status_baru, ':no' => $no_kamar]);
    echo "<script>alert('Data kamar diperbarui!'); window.location='c-kelola_kamar.php';</script>";
}

// Ambil Semua Data Kamar
$data_kamar = $connect->query("SELECT * FROM kamar ORDER BY nomor_kamar ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Kelola Kamar</title>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <nav class="bg-red-900 text-white p-6 shadow-md flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2"><span class="text-2xl">🛏️</span>
            <h1 class="text-xl font-bold">Kelola Data Kamar</h1>
        </div>
        <a href="dashboard.php" class="text-gray-200 hover:text-white text-sm">← Kembali ke Dashboard</a>
    </nav>

    <div class="max-w-6xl mx-auto p-8 grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="md:col-span-1 h-fit">
            <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-red-800">
                <h3 class="text-lg font-bold mb-4">➕ Tambah Kamar Baru</h3>
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor Kamar</label>
                        <input type="number" name="nomor_kamar" required placeholder="Contoh: 205"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tipe Kamar</label>
                        <select name="tipe_kamar"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                            <option value="1">Tipe 1 (Standard)</option>
                            <option value="2">Tipe 2 (Deluxe)</option>
                            <option value="3">Tipe 3 (Suite)</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_kamar"
                        class="w-full bg-red-800 text-white font-bold py-2 rounded-lg hover:bg-red-900 transition shadow">
                        Simpan Kamar
                    </button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="p-4">No. Kamar</th>
                            <th class="p-4">Tipe</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        <?php foreach ($data_kamar as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <form method="POST">
                                    <td class="p-4 font-bold text-gray-800">
                                        <?= $row['nomor_kamar'] ?>
                                        <input type="hidden" name="nomor_kamar" value="<?= $row['nomor_kamar'] ?>">
                                    </td>
                                    <td class="p-4">
                                        <input type="text" name="tipe_kamar" value="<?= $row['tipe_kamar'] ?>"
                                            class="w-16 border rounded px-1 text-center bg-gray-50">
                                    </td>
                                    <td class="p-4">
                                        <select name="status" class="text-xs p-1 rounded border bg-white">
                                            <option value="belum_dipesan" <?= $row['status'] == 'belum_dipesan' ? 'selected' : '' ?>>Kosong (Belum Dipesan)</option>
                                            <option value="dipesan" <?= $row['status'] == 'dipesan' ? 'selected' : '' ?>>
                                                Dipesan (Booked)</option>
                                            <option value="ditempati" <?= $row['status'] == 'ditempati' ? 'selected' : '' ?>>
                                                Terisi (Check-In)</option>
                                        </select>
                                    </td>
                                    <td class="p-4 flex items-center justify-center gap-2">
                                        <button type="submit" name="update_kamar"
                                            class="text-blue-600 hover:text-blue-800 font-bold text-xs">💾 Save</button>
                                        <span class="text-gray-300">|</span>
                                        <a href="?hapus_id=<?= $row['nomor_kamar'] ?>"
                                            onclick="return confirm('Hapus kamar <?= $row['nomor_kamar'] ?>?')"
                                            class="text-red-600 hover:text-red-800 font-bold text-xs">❌ Hapus</a>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (count($data_kamar) == 0): ?>
                    <p class="p-6 text-center text-gray-400 italic">Belum ada data kamar.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</body>

</html>