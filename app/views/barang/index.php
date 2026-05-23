<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Inventaris Barang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel ="stylesheet" href="../assets/css/style.css"> 
</head>
<body>

<div class="container">
        
        <div class="header-container" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ffe5e0; padding-bottom: 20px; margin-bottom: 25px;">
            <div>
                <h2 style="margin: 0; border: none;">Daftar Inventaris Barang</h2>
                <p style="margin: 5px 0 0 0; color: #64748b; font-size: 14px;">
                    Selamat datang, <strong style="color: #ff8e53;"><?= htmlspecialchars($_SESSION['nama_lengkap']); ?></strong> 👋
                </p>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <a href="index.php?action=create" class="btn-tambah"><i class="fas fa-plus"></i>Tambah Barang Baru</a>
                <a href="logout.php" class="btn-kembali" style="margin: 0; gap: 5px; border-color: #ff7675; color: #d63031;"><i class="fas fa-sign-out-alt"></i>  Logout</a>
            </div>
        </div>

    <form action="index.php" method="GET" class="search-form">
        <input type="text" name="cari" placeholder="Cari nama, kode, kategori..." 
        class="search-input" value="<?= htmlspecialchars($keyword ?? ''); ?>">
    
    <button type="submit" class="btn-search"><i class="fas fa-search"></i> Cari</button>
    
    <a href="index.php" class="btn-reset" title="Reset Pencarian"><i class="fas fa-undo-alt"></i> Reset</a>
</form>
    <div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Foto</th>
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
              
                if (!empty($data)) {
                    foreach ($data as $row) { 
                ?>
                <tr>
                    <td style="color: #94a3b8; font-weight: 600;"><?= $no++; ?></td>
                    <td class="kode"><?= htmlspecialchars($row['kode_barang']); ?></td>
                    <td>
                        <?php if (!empty($row['foto'])): ?>
                            <img src="../assets/uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto Barang" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                        <?php else: ?>
                            <span style="background: #f1f5f9; color: #94a3b8; padding: 4px 8px; border-radius: 4px; font-size: 11px;">No Image</span>
                        <?php endif; ?>
                    </td>
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
                            <a href="index.php?action=detail&id=<?= $row['id']; ?>" class="btn-icon btn-detail" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="index.php?action=edit&id=<?= $row['id']; ?>" class="btn-icon btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="index.php?action=hapus&id=<?= $row['id']; ?>" class="btn-icon btn-hapus" onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?');" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='9' style='text-align:center; padding: 40px; color: #94a3b8;'><i class='fas fa-box-open fa-2x' style='margin-bottom:10px;'></i><br>Belum ada data barang.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>