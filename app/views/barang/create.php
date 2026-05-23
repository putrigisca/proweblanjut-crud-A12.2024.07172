<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Barang</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Tambah Barang Baru</h2>
    <?php if (!empty($errors)): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 15px 20px; border-radius: 8px; border-left: 6px solid #dc2626; margin-bottom: 20px;">
                <p style="margin: 0 0 8px 0; font-weight: bold;">⚠️ Gagal Menyimpan Data:</p>
                <ul style="margin: 0; padding-left: 20px; font-weight: 500;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
    <?php endif; ?>

   <form action="index.php?action=store" method="POST" enctype="multipart/form-data">
    <div class="foto-center-wrapper">
        <div class="form-group-card card-foto">
            <label>Foto Barang</label>
                
        <?php if (isset($barang['foto']) && !empty($barang['foto']) && file_exists('../assets/uploads/' . $barang['foto'])): ?>
                <img src="../assets/uploads/<?= htmlspecialchars($barang['foto']); ?>" alt="<?= htmlspecialchars($barang['nama_barang']); ?>" class="foto-detail">
                <?php else: ?>
                <img src="" id="preview-img" class="foto-detail" style="max-width: 200px; display: none; margin: 0 auto 10px;">
            <div class="no-foto" id="text-no-foto">
                <p style="margin: 0;">Tidak ada foto, silahkan unggah.</p>
            </div>
        <?php endif; ?>

                <div style="margin-top: 10px;">
                <label for="foto" style="font-size: 13px; color: #555;">Unggah Foto Baru (Max 1MB)</label>
                <input type="file" name="foto" id="foto" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
            </div>
        </div>
    </div>
    
        <div class="form-grid-2">
        <div class="form-group-card">
            <label> Kode Barang </label>
            <input type="text" name="kode_barang" value="<?= isset($_POST['kode_barang']) ? htmlspecialchars($_POST['kode_barang']) : $kode_otomatis; ?>" readonly>
        </div>
        <div class="form-group-card">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= isset($_POST['nama_barang']) ? htmlspecialchars($_POST['nama_barang']) : ''; ?>" required>
        </div>
        <div class="form-group-card">
                <label>Warna</label>
                <?php $warna_post = isset($_POST['warna']) ? htmlspecialchars($_POST['warna']) : ''; ?>
                <select name="warna">
                    <option value="">Pilih Warna</option>
                    <option value="Merah"<?= $warna_post == 'Merah' ? 'selected' : ''; ?>>Merah</option>
                    <option value="Biru"<?= $warna_post == 'Biru' ? 'selected' : ''; ?>>Biru</option>
                    <option value="Hijau"<?= $warna_post == 'Hijau' ? 'selected' : ''; ?>>Hijau</option>
                    <option value="Kuning"<?= $warna_post == 'Kuning' ? 'selected' : ''; ?>>Kuning</option>
                    <option value="Hitam"<?= $warna_post == 'Hitam' ? 'selected' : ''; ?>>Hitam</option>
                    <option value="Putih"<?= $warna_post == 'Putih' ? 'selected' : ''; ?>>Putih</option>
                    <option value="Abu-abu"<?= $warna_post == 'Abu-abu' ? 'selected' : ''; ?>>Abu-abu</option>
                    <option value="Coklat"<?= $warna_post == 'Coklat' ? 'selected' : ''; ?>>Coklat</option>
                    <option value="Ungu"<?= $warna_post == 'Ungu' ? 'selected' : ''; ?>>Ungu</option>
                    <option value="Pink"<?= $warna_post == 'Pink' ? 'selected' : ''; ?>>Pink</option>
                    <option value="Lainnya"<?= $warna_post == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
            </select>
        </div>
        <div class="form-group-card">
            <label>Kategori</label>
            <?php $kategori_post = isset($_POST['kategori']) ? htmlspecialchars($_POST['kategori']) : ''; ?>
            <select name="kategori" required>
                <option value="">Pilih Kategori</option>
                <option value="Bahan Baku"<?= $kategori_post == 'Bahan Baku' ? 'selected' : ''; ?>>Bahan Baku</option>
                <option value="Pakaian"<?= $kategori_post == 'Pakaian' ? 'selected' : ''; ?>>Pakaian</option>
                <option value="Makanan"<?= $kategori_post == 'Makanan' ? 'selected' : ''; ?>>Makanan</option>
                <option value="Minuman"<?= $kategori_post == 'Minuman' ? 'selected' : ''; ?>>Minuman</option>
                <option value="Alat tulis"<?= $kategori_post == 'Alat tulis' ? 'selected' : ''; ?>>Alat tulis</option>
                <option value="Elektronik"<?= $kategori_post == 'Elektronik' ? 'selected' : ''; ?>>Elektronik</option>
                <option value="Peralatan Olahraga"<?= $kategori_post == 'Peralatan Olahraga' ? 'selected' : ''; ?>>Peralatan Olahraga</option>
                <option value="Kemasan"<?= $kategori_post == 'Kemasan' ? 'selected' : ''; ?>>Kemasan</option>
                <option value="Lainnya"<?= $kategori_post == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
            </select>
        </div>
        <div class="form-group-card">
            <label>Jumlah</label>
            <input type="number" name="jumlah" value="<?= isset($_POST['jumlah']) ? htmlspecialchars($_POST['jumlah']) : ''; ?>" required>
        </div>
        <div class="form-group-card">
            <label>Satuan</label>
            <input type="text" name="satuan" value="<?= isset($_POST['satuan']) ? htmlspecialchars($_POST['satuan']) : ''; ?>" required placeholder="pcs/kg/liter/pack/lusin/lainnya">
        </div>
        <div class="form-group-card">
            <label>Harga</label>
            <input type="number" name="harga" value="<?= isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : ''; ?>" required>
        </div>
        <div class="form-group-card">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" required value="<?= isset($_POST['tanggal_masuk']) ? $_POST['tanggal_masuk'] : date('Y-m-d'); ?>">
        </div>
    </div>
        <div class="form-group-card form-group-full ">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : ''; ?></textarea>
        </div>
        <div class="action-buttons">
            <a href="index.php" class="btn btn-kembali">Kembali</a>
            <button type="submit" class="btn btn-simpan">Simpan Data</button>
        </div>
    </form>
</div>
<script>
    function previewImage(event) {
        const preview = document.getElementById('preview-img');
        const noFoto = document.getElementById('text-no-foto');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                if (preview) {
                    preview.src = reader.result;
                    preview.style.display = 'block'; 
                }
                if (noFoto) {
                    noFoto.style.display = 'none'; 
                }
            }
            reader.readAsDataURL(file);
        }
    }
</script>
</body>
</html>