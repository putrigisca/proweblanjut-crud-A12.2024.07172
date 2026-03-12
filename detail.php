<?php
require_once 'koneksi.php';


if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = :id");
$stmt->execute([':id' => $id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    die("Data tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Barang - <?= htmlspecialchars($barang['nama_barang']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container container-form">
    <h2>Detail Barang</h2>
    <hr>
    
    <div class="form-grid-2">
    <div class="form-group-card">
        <label>Kode Barang</label>
        <input type="text" value="<?= htmlspecialchars($barang['kode_barang']); ?>" readonly>
    </div>
    
    <div class="form-group-card">
        <label>Nama Barang</label>
        <input type="text" value="<?= htmlspecialchars($barang['nama_barang']); ?>" readonly>
    </div>
    
    <div class="form-group-card">
            <label>Warna</label>
            <input type="text" value="<?= htmlspecialchars($barang['warna'] ?? 'Tidak ada'); ?>" readonly>
    </div>

    <div class="form-group-card">
        <label>Kategori</label>
        <input type="text" value="<?= htmlspecialchars($barang['kategori']); ?>" readonly>
    </div>
    
    <div class="form-group-card">
        <label>Jumlah</label>
        <input type="text" value="<?= htmlspecialchars($barang['jumlah']); ?>" readonly>
    </div>
    
    <div class="form-group-card">
        <label>Satuan</label>
        <input type="text" value="<?= htmlspecialchars($barang['satuan']); ?>" readonly>
    </div>
    
    <div class="form-group-card">
        <label>Harga</label>
        <input type="text" value="Rp <?= number_format($barang['harga'], 0, ',', '.'); ?>" readonly>
    </div>
    
    <div class="form-group-card">
        <label>Tanggal Masuk</label>
        <input type="text" value="<?= htmlspecialchars($barang['tanggal_masuk']); ?>" readonly>
    </div>
</div>
    
    <div class="form-group form-group-full">
        <label>Deskripsi</label>
        <textarea rows="3" readonly><?= htmlspecialchars($barang['deskripsi']); ?></textarea>
    </div>

    <div class="action-buttons">
        <a href="index.php" class="btn btn-kembali">Kembali</a>
        <a href="edit.php?id=<?= $barang['id']; ?>" class="btn-simpan btn-warning">Edit Data</a>
    </div>
    </div>
</div>
</body>
</html>

