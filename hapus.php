<?php
require_once 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt_foto = $pdo->prepare("SELECT foto FROM barang WHERE id = ?");
    $stmt_foto->execute([$id]);
    $barang = $stmt_foto->fetch(PDO::FETCH_ASSOC);

    if ($barang && !empty($barang['foto'])) {
        $file_path = 'uploads/' . $barang['foto'];
        if (file_exists($file_path)) {
            unlink($file_path); // Ini fungsi dari PDF dosenmu untuk menghapus file!
        }
    }

    $stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit;
?>