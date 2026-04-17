<?php
require_once 'koneksi.php';


$query = $pdo->query("SELECT MAX(SUBSTRING(kode_barang, 5)) as max_kode FROM barang");
$data = $query->fetch(PDO::FETCH_ASSOC);
$next = ($data['max_kode'] ?? 0) + 1;
$kode_otomatis = "BRG-" . str_pad($next, 3, '0', STR_PAD_LEFT);

$errors = [];
$nama_foto_baru = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang   = htmlspecialchars($_POST['kode_barang']);
    $nama_barang   = htmlspecialchars($_POST['nama_barang']);
    $warna         = htmlspecialchars($_POST['warna']);
    $kategori      = htmlspecialchars($_POST['kategori']);
    $deskripsi     = htmlspecialchars($_POST['deskripsi']);
    $jumlah        = htmlspecialchars($_POST['jumlah']);
    $satuan        = htmlspecialchars($_POST['satuan']);
    $harga         = htmlspecialchars($_POST['harga']);
    $tanggal_masuk = htmlspecialchars($_POST['tanggal_masuk']);
    
    if (empty(trim($nama_barang))) {
        $errors[] = "Nama barang tidak boleh kosong.";
    }
    if (!is_numeric($jumlah) || !is_numeric($harga)) {
        $errors[] = "Jumlah dan Harga harus berupa angka.";
    }

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $tmpName  = $_FILES['foto']['tmp_name'];

        $validExtensions = ['jpg', 'jpeg', 'png'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $validExtensions)) {
            $errors[] = "Gagal: Ekstensi file hanya boleh JPG, JPEG, atau PNG.";
        } 
        elseif ($fileSize > 1000000) { 
            $errors[] = "Gagal Upload Foto: Ukuran foto maksimal 1 MB.";
        } 
        else {
            $nama_foto_baru = uniqid() . '.' . $fileExt; 
            $tmp_file_path = $tmpName;
        }
    } else {
        $errors[] = "Gagal: Foto barang wajib diunggah.";
    }

    if (empty($errors)) {
    try {
        move_uploaded_file($tmpName, 'uploads/' . $nama_foto_baru);
        $sql = "INSERT INTO barang (kode_barang,foto, nama_barang, warna ,kategori, deskripsi, jumlah, satuan, harga, tanggal_masuk) 
                VALUES (:kode_barang, :foto, :nama_barang, :warna, :kategori, :deskripsi, :jumlah, :satuan, :harga, :tanggal_masuk)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':kode_barang'   => $kode_barang,
            ':foto'          => $nama_foto_baru,
            ':nama_barang'   => $nama_barang,
            ':warna'         => $warna,
            ':kategori'      => $kategori,
            ':deskripsi'     => $deskripsi,
            ':jumlah'        => $jumlah,
            ':satuan'        => $satuan,
            ':harga'         => $harga,
            ':tanggal_masuk' => $tanggal_masuk
        ]);

        header("Location: index.php");
        exit;
        } catch (PDOException $e) {
            $errors[] = "Gagal menambah data: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Barang</title>
    <link rel="stylesheet" href="css/style.css">
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

    <form method="POST" enctype="multipart/form-data">
        <div class="form-grid-2">
        <div class="form-group-card">
            <label> Kode Barang </label>
            <input type="text" name="kode_barang" value="<?= isset($_POST['kode_barang']) ? htmlspecialchars($_POST['kode_barang']) : $kode_otomatis; ?>" readonly>
        </div>
        <div class="form-group-card">
            <label for="foto">Foto Barang (jpg/png/jpeg, Max 1MB):</label>
            <input type="file" name="foto" id="foto" accept=".jpg, .jpeg, .png" required>
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
</body>
</html>