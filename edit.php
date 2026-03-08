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
    $kategori      = $_POST['kategori'];
    $deskripsi     = $_POST['deskripsi'];
    $jumlah        = $_POST['jumlah'];
    $satuan        = $_POST['satuan'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    try {
        $sql = "UPDATE barang SET 
                kode_barang = :kode_barang, 
                nama_barang = :nama_barang, 
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
            ':nama_barang'   => $nama_barang,
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
        $error_message = "Gagal mengupdate data: " . $e->getMessage();
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
<div class="container container-form">
    <h2>Edit Data Barang</h2>
    <?php if (isset($error_message)): ?>
        <p style="color:red;"><?= $error_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" value="<?= htmlspecialchars($barang['kode_barang']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']); ?>" required>
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" required>
                <option value="Bahan Baku" <?= $barang['kategori'] == 'Bahan Baku' ? 'selected' : ''; ?>>Bahan Baku</option>
                <option value="Peralatan" <?= $barang['kategori'] == 'Peralatan' ? 'selected' : ''; ?>>Peralatan</option>
                <option value="Kemasan" <?= $barang['kategori'] == 'Kemasan' ? 'selected' : ''; ?>>Kemasan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"><?= htmlspecialchars($barang['deskripsi']); ?></textarea>
        </div>
        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" value="<?= htmlspecialchars($barang['jumlah']); ?>" required>
        </div>
        <div class="form-group">
            <label>Satuan</label>
            <input type="text" name="satuan" value="<?= htmlspecialchars($barang['satuan']); ?>" required placeholder="pcs/kg/liter">
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($barang['harga']); ?>" required>
        </div>
        <div class="form-group">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="<?= htmlspecialchars($barang['tanggal_masuk']); ?>" required>
        </div>
        <div class="form-group">
            <a href="index.php" class="btn btn-kembali">Kembali</a>
            <button type="submit" class="btn btn-simpan">Simpan Perubahan</button>
        </div>
    </form>
</div>
</body>
</html>
