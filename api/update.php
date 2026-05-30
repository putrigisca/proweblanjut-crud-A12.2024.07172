<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/database.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!empty($input['id'])) {
    $sql = "UPDATE barang SET 
            kode_barang = :kode_barang,
            nama_barang = :nama_barang, 
            warna = :warna,
            kategori = :kategori,
            jumlah = :jumlah, 
            satuan = :satuan,
            harga = :harga, 
            tanggal_masuk = :tanggal_masuk,
            deskripsi = :deskripsi,
            foto = :foto
            WHERE id = :id";
            
    $stmt = $pdo->prepare($sql);
    $sukses = $stmt->execute([
        ':kode_barang'   => isset($input['kode_barang']) ? $input['kode_barang'] : '',
        ':nama_barang'   => isset($input['nama_barang']) ? $input['nama_barang'] : '',
        ':warna'         => isset($input['warna']) ? $input['warna'] : '',
        ':kategori'      => isset($input['kategori']) ? $input['kategori'] : '',
        ':jumlah'        => isset($input['jumlah']) ? $input['jumlah'] : 0,
        ':satuan'        => isset($input['satuan']) ? $input['satuan'] : '',
        ':harga'         => isset($input['harga']) ? $input['harga'] : 0,
        ':tanggal_masuk' => isset($input['tanggal_masuk']) ? $input['tanggal_masuk'] : date('Y-m-d'),
        ':deskripsi'     => isset($input['deskripsi']) ? $input['deskripsi'] : '',
        ':foto'          => isset($input['foto']) ? $input['foto'] : '',
        ':id'            => $input['id']
    ]);

    if ($sukses) {
        echo json_encode(["status" => "berhasil", "message" => "Data barang berhasil diupdate sepenuhnya!"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "gagal", "message" => "Gagal update database"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "gagal", "message" => "ID barang wajib dikirim"]);
}
?>