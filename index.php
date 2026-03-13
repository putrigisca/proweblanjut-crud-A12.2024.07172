<?php
require_once 'koneksi.php';

$keyword = '';
if (isset($_GET['cari'])) {
    $keyword = $_GET['cari'];
    $sql = "SELECT * FROM barang WHERE kode_barang LIKE :keyword OR nama_barang LIKE :keyword OR kategori LIKE :keyword ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':keyword', "%$keyword%");
    $stmt->execute();
} else {
    $sql = "SELECT * FROM barang ORDER BY id DESC";
    $stmt = $pdo->query($sql);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Inventaris Barang</title>
    <link rel ="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <div class="header-container">
            <h2>Daftar Inventaris Barang</h2>
            <a href="tambah.php" class="btn-tambah">Tambah Barang Baru</a>
        </div>

    <form action="index.php" method="GET" class="search-form">
        <input type="text" name="cari" placeholder="Cari nama, kode, kategori..." 
        class="search-input" value="<?= htmlspecialchars($keyword ?? ''); ?>">
    
    <button type="submit" class="btn-search">Cari</button>
    
    <a href="index.php" class="btn-reset" title="Reset Pencarian">Reset</a>
</form>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Tanggal Masuk</th>
                    <th style="width: 140px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                ?>
                <tr>
                    <td style="color: #94a3b8; font-weight: 600;"><?= $no++; ?></td>
                    <td class="kode"><?= htmlspecialchars($row['kode_barang']); ?></td>
                    <td style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($row['nama_barang']); ?></td>
                    <td>
                        <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                            <?= htmlspecialchars($row['kategori']); ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($row['jumlah']); ?> <span style="color: #94a3b8; font-size: 12px;"><?= htmlspecialchars($row['satuan']); ?></span></td>
                    <td class="harga">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_masuk'])); ?></td>
                    <td>
                        <div class="action-group" style="justify-content: center;">
                            <a href="detail.php?id=<?= $row['id']; ?>" class="btn-aksi btn-detail">Detail</a>
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn-aksi btn-edit">Edit</a>
                            <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?');">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php 
                } 
                
                if ($stmt->rowCount() == 0) {
                    echo "<tr><td colspan='8' style='text-align:center; padding: 40px; color: #94a3b8;'><br>Belum ada data barang.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>