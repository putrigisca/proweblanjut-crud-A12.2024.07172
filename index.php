<?php
require_once 'koneksi.php';

$sql = "SELECT * FROM barang ORDER BY id DESC";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Inventaris Barang</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel ="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="header-container">
            <h2>Daftar Inventaris Barang</h2>
            <a href="tambah.php" class="btn-tambah"><i class="fas fa-plus"></i> Tambah Barang Baru</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
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
                    <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                    <td><?= htmlspecialchars($row['jumlah']); ?> <span style="color: #94a3b8; font-size: 12px;"><?= htmlspecialchars($row['satuan']); ?></span></td>
                    <td class="harga">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_masuk'])); ?></td>
                    <td>
                        <div class="action-group">
                            <a href="detail.php?id=<?= $row['id']; ?>" class="btn-icon btn-detail" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn-icon btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-icon btn-hapus" onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?');" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                } 
                
                if ($stmt->rowCount() == 0) {
                    echo "<tr><td colspan='9' style='text-align:center; padding: 40px; color: #94a3b8;'><i class='fas fa-box-open fa-2x' style='margin-bottom:10px;'></i><br>Belum ada data barang.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>