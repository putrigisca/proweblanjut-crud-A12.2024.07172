<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Barang - <?= htmlspecialchars($barang['nama_barang']); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Detail Barang</h2>

    <div class="foto-center-wrapper">
    <div class="form-group-card card-foto">
        <label>Foto Barang</label>
        <?php if (!empty($barang['foto']) && file_exists('../assets/uploads/' . $barang['foto'])): ?>
            <img src="../assets/uploads/<?= htmlspecialchars($barang['foto']); ?>" alt="<?= htmlspecialchars($barang['nama_barang']); ?>" class="foto-detail">
        <?php else: ?>
            <div class="no-foto">
            <p style="margin: 0;">Tidak ada foto untuk barang ini.</p>
            </div>
        <?php endif; ?>
    </div>
    </div>

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
        <a href="index.php?action=edit&id=<?= $barang['id']; ?>" class="btn-simpan btn-warning">Edit Data</a>
    </div>
    </div>
</div>
</body>
</html>