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
    
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" value="<?= htmlspecialchars($barang['kode_barang']); ?>" readonly>
    </div>
    
    <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" value="<?= htmlspecialchars($barang['nama_barang']); ?>" readonly>
    </div>
    
    <div class="form-group">
        <label>Kategori</label>
        <input type="text" value="<?= htmlspecialchars($barang['kategori']); ?>" readonly>
    </div>
    
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea rows="3" readonly><?= htmlspecialchars($barang['deskripsi']); ?></textarea>
    </div>
    
    <div class="form-group">
        <label>Jumlah</label>
        <input type="text" value="<?= htmlspecialchars($barang['jumlah']); ?>" readonly>
    </div>
    
    <div class="form-group">
        <label>Satuan</label>
        <input type="text" value="<?= htmlspecialchars($barang['satuan']); ?>" readonly>
    </div>
    
    <div class="form-group">
        <label>Harga</label>
        <input type="text" value="Rp <?= number_format($barang['harga'], 0, ',', '.'); ?>" readonly>
    </div>
    
    <div class="form-group">
        <label>Tanggal Masuk</label>
        <input type="text" value="<?= htmlspecialchars($barang['tanggal_masuk']); ?>" readonly>
    </div>
    
    <div class="form-group">
        <a href="index.php" class="btn btn-kembali">Kembali</a>
        <a href="edit.php?id=<?= $barang['id']; ?>" class="btn-simpan btn-warning">Edit Data</a>
    </div>
    </div>
</div>
</body>
</html>

