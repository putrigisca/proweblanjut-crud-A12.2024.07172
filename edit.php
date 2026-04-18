<?php
require_once 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->execute([$id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    die("Data tidak ditemukan!");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang   = $_POST['kode_barang'];
    $nama_barang   = $_POST['nama_barang'];
    $warna         = $_POST['warna'];
    $kategori      = $_POST['kategori'];
    $deskripsi     = $_POST['deskripsi'];
    $jumlah        = $_POST['jumlah'];
    $satuan        = $_POST['satuan'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $errors = [];
    if (empty(trim($nama_barang))) {
        $errors[] = "Nama barang tidak boleh kosong.";
    }
    if (!is_numeric($jumlah) || !is_numeric($harga)) {
        $errors[] = "Jumlah dan Harga harus berupa angka.";
    }

    $nama_foto_baru = $barang['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $tmpName  = $_FILES['foto']['tmp_name'];

        $validExtensions = ['jpg', 'jpeg', 'png'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $validExtensions)) {
            $errors[] = "Gagal: Ekstensi file hanya boleh JPG, JPEG, atau PNG.";
        } 
        elseif ($fileSize > 1000000) { // Batas 1 MB sesuai modul
            $errors[] = "Gagal Upload Foto: Ukuran foto maksimal 1 MB.";
        } 
        else {
            $nama_foto_baru = uniqid() . '.' . $fileExt; 
            $tmp_file_path = $tmpName;
        }
    }
    if (empty($errors)) {
    try {
        if (isset($tmp_file_path)) {
                 move_uploaded_file($tmp_file_path, 'uploads/' . $nama_foto_baru);
            }
        $sql = "UPDATE barang SET 
                kode_barang = :kode_barang, 
                foto = :foto,
                nama_barang = :nama_barang,
                warna = :warna,
                kategori = :kategori, 
                deskripsi = :deskripsi, 
                jumlah = :jumlah, 
                satuan = :satuan, 
                harga = :harga, 
                tanggal_masuk = :tanggal_masuk 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':kode_barang'   => $kode_barang,
            ':foto'           => $nama_foto_baru,
            ':nama_barang'   => $nama_barang,
            ':warna'         => $warna,
            ':kategori'      => $kategori,
            ':deskripsi'     => $deskripsi,
            ':jumlah'        => $jumlah,
            ':satuan'        => $satuan,
            ':harga'         => $harga,
            ':tanggal_masuk' => $tanggal_masuk,
            ':id'            => $id
        ]);

        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $errors[] = "Gagal mengupdate data: " . $e->getMessage();
    }
}   
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Barang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Edit Data Barang</h2>
    <?php if (!empty($errors)): ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 15px 20px; border-radius: 8px; border-left: 6px solid #dc2626; margin-bottom: 20px;">
                <p style="margin: 0 0 8px 0; font-weight: bold;">⚠️ Gagal Mengupdate Data:</p>
                <ul style="margin: 0; padding-left: 20px; font-weight: 500;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
    <div class="foto-center-wrapper">
    <div class="form-group-card card-foto">
        <label>Foto Barang</label>
            <?php if (!empty($barang['foto']) && file_exists('uploads/' . $barang['foto'])): ?>
                <img src="uploads/<?= htmlspecialchars($barang['foto']); ?>" alt="<?= htmlspecialchars($barang['nama_barang']); ?>" class="foto-detail">
            <?php else: ?>
                <div class="no-foto">
                <p style="margin: 0;">Tidak ada foto untuk barang ini, silahkan unggah foto.</p>
                </div>
            <?php endif; ?>
            <div style="margin-top: 10px;">
            <label for="foto" style="font-size: 13px; color: #555;">Ganti/Unggah Foto Baru (Max 1MB) </label>
            <input type="file" name="foto" id="foto" accept=".jpg, .jpeg, .png">
        </div>
    </div>
    </div>
    <div class="form-grid-2">
        <div class="form-group-card">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" value="<?= isset($_POST['kode_barang']) ? htmlspecialchars($_POST['kode_barang']) : htmlspecialchars($barang['kode_barang']); ?>" readonly>
        </div>

        <div class="form-group-card">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= isset($_POST['nama_barang']) ? htmlspecialchars($_POST['nama_barang']) : htmlspecialchars($barang['nama_barang']); ?>" required>
        </div>

        <div class="form-group-card">
            <label>Warna</label>
            <?php $warna_val = isset($_POST['warna']) ? htmlspecialchars($_POST['warna']) : $barang['warna']; ?>
            <select name="warna"  required>
                <option value="">Pilih Warna</option>
                <option value="Merah" <?= $warna_val == 'Merah' ? 'selected' : ''; ?>>Merah</option>
                <option value="Biru" <?= $warna_val == 'Biru' ? 'selected' : ''; ?>>Biru</option>
                <option value="Hijau" <?= $warna_val == 'Hijau' ? 'selected' : ''; ?>>Hijau</option>
                <option value="Kuning" <?= $warna_val == 'Kuning' ? 'selected' : ''; ?>>Kuning</option>
                <option value="Hitam" <?= $warna_val == 'Hitam' ? 'selected' : ''; ?>>Hitam</option>
                <option value="Putih" <?= $warna_val == 'Putih' ? 'selected' : ''; ?>>Putih</option>
                <option value="Abu-abu" <?= $warna_val == 'Abu-abu' ? 'selected' : ''; ?>>Abu-abu</option>
                <option value="Coklat" <?= $warna_val == 'Coklat' ? 'selected' : ''; ?>>Coklat</option>
                <option value="Ungu" <?= $warna_val == 'Ungu' ? 'selected' : ''; ?>>Ungu</option>
                <option value="Pink" <?= $warna_val == 'Pink' ? 'selected' : ''; ?>>Pink</option>
                <option value="Lainnya" <?= $warna_val == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
    </select>
        </div>
        <div class="form-group-card">
            <label>Kategori</label>
            <?php $kategori_val = isset($_POST['kategori']) ? htmlspecialchars($_POST['kategori']) : $barang['kategori']; ?>
            <select name="kategori" required>
                <option value="Bahan Baku" <?= $kategori_val == 'Bahan Baku' ? 'selected' : ''; ?>>Bahan Baku</option>
                <option value="Pakaian" <?$kategori_val == 'Pakaian' ? 'selected' : ''; ?>>Pakaian</option>
                <option value="Makanan" <?= $kategori_val == 'Makanan' ? 'selected' : ''; ?>>Makanan</option>
                <option value="Minuman" <?= $kategori_val == 'Minuman' ? 'selected' : ''; ?>>Minuman</option>
                <option value="Alat tulis" <?= $kategori_val == 'Alat tulis' ? 'selected' : ''; ?>>Alat tulis</option>
                <option value="Elektronik" <?= $kategori_val == 'Elektronik' ? 'selected' : ''; ?>>Elektronik</option>
                <option value="Peralatan Olahraga" <?= $kategori_val == 'Peralatan Olahraga' ? 'selected' : ''; ?>>Peralatan Olahraga</option>
                <option value="Kemasan" <?= $kategori_val == 'Kemasan' ? 'selected' : ''; ?>>Kemasan</option>
                <option value="Lainnya" <?= $kategori_val == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
            </select>
        </div>
        <div class="form-group-card">
            <label>Jumlah</label>
            <input type="number" name="jumlah" value="<?= isset($_POST['jumlah']) ? htmlspecialchars($_POST['jumlah']) : htmlspecialchars($barang['jumlah']); ?>" required>
        </div>
        <div class="form-group-card">
            <label>Satuan</label>
            <input type="text" name="satuan" value="<?= isset($_POST['satuan']) ? htmlspecialchars($_POST['satuan']) : htmlspecialchars($barang['satuan']); ?>" required placeholder="pcs/kg/liter/pack/lusin/lainnya">
        </div>
        <div class="form-group-card">
            <label>Harga</label>
            <input type="number" name="harga" value="<?= isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : htmlspecialchars($barang['harga']); ?>" required>
        </div>
        <div class="form-group-card">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="<?= isset($_POST['tanggal_masuk']) ? htmlspecialchars($_POST['tanggal_masuk']) : htmlspecialchars($barang['tanggal_masuk']); ?>" required>
        </div>
    </div>
        <div class="form-group form-group-full">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : htmlspecialchars($barang['deskripsi']); ?></textarea>
        </div>
        <div class="action-buttons">
            <a href="index.php" class="btn btn-kembali">Kembali</a>
            <button type="submit" class="btn btn-simpan">Simpan Perubahan</button>
        </div>
    </form>
</div>
</body>
</html>
