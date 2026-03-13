<?php
require_once 'koneksi.php';


$query = $pdo->query("SELECT MAX(SUBSTRING(kode_barang, 5)) as max_kode FROM barang");
$data = $query->fetch(PDO::FETCH_ASSOC);
$next = ($data['max_kode'] ?? 0) + 1;
$kode_otomatis = "BRG-" . str_pad($next, 3, '0', STR_PAD_LEFT);


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

    try {
        $sql = "INSERT INTO barang (kode_barang, nama_barang, warna ,kategori, deskripsi, jumlah, satuan, harga, tanggal_masuk) 
                VALUES (:kode_barang, :nama_barang, :warna, :kategori, :deskripsi, :jumlah, :satuan, :harga, :tanggal_masuk)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':kode_barang'   => $kode_barang,
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
        $error_message = "Gagal menambah data: " . $e->getMessage();
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
<div class="container container-form ">
    <h2>Tambah Barang Baru</h2>
    <?php if (isset($error_message)): ?>
        <p style="color:red;"><?= $error_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-grid-2">
        <div class="form-group-card">
            <label> Kode Barang </label>
            <input type="text" name="kode_barang" value="<?= $kode_otomatis; ?>" readonly>
        </div>
        <div class="form-group-card">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" required>
        </div>
        <div class="form-group-card">
                <label>Warna</label>
                <select name="warna">
                    <option value="">Pilih Warna</option>
                    <option value="Merah">Merah</option>
                    <option value="Biru">Biru</option>
                    <option value="Hijau">Hijau</option>
                    <option value="Kuning">Kuning</option>
                    <option value="Hitam">Hitam</option>
                    <option value="Putih">Putih</option>
                    <option value="Abu-abu">Abu-abu</option>
                    <option value="Coklat">Coklat</option>
                    <option value="Ungu">Ungu</option>
                    <option value="Pink">Pink</option>
                    <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="form-group-card">
            <label>Kategori</label>
            <select name="kategori" required>
                <option value="">Pilih Kategori</option>
                <option value="Bahan Baku">Bahan Baku</option>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
                <option value="Alat tulis">Alat tulis</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Peralatan Olahraga">Peralatan Olahraga</option>
                <option value="Kemasan">Kemasan</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="form-group-card">
            <label>Jumlah</label>
            <input type="number" name="jumlah" required>
        </div>
        <div class="form-group-card">
            <label>Satuan</label>
            <input type="text" name="satuan" required placeholder="pcs/kg/liter/pack/lusin/lainnya">
        </div>
        <div class="form-group-card">
            <label>Harga</label>
            <input type="number" name="harga" required>
        </div>
        <div class="form-group-card">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" required value="<?= date('Y-m-d'); ?>">
        </div>
    </div>
        <div class="form-group-card form-group-full ">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"></textarea>
        </div>
        <div class="action-buttons">
            <a href="index.php" class="btn btn-kembali">Kembali</a>
            <button type="submit" class="btn btn-simpan">Simpan Data</button>
        </div>
    </form>
</div>
</body>
</html>