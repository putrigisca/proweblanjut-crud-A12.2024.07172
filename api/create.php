<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/database.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!empty($input['nama_barang']) && !empty($input['kategori']) && isset($input['jumlah']) && !empty($input['satuan']) && isset($input['harga']) && !empty($input['tanggal_masuk'])) {
    
    $sql = "INSERT INTO barang (kode_barang, nama_barang, warna, kategori, jumlah, satuan, harga, tanggal_masuk, deskripsi, foto) 
            VALUES (:kode_barang, :nama_barang, :warna, :kategori, :jumlah, :satuan, :harga, :tanggal_masuk, :deskripsi, :foto)";
    
    $stmt = $pdo->prepare($sql);
    $sukses = $stmt->execute([
        ':kode_barang'   => isset($input['kode_barang']) ? $input['kode_barang'] : '',
        ':nama_barang'   => $input['nama_barang'],
        ':warna'         => isset($input['warna']) ? $input['warna'] : '',
        ':kategori'      => $input['kategori'],
        ':jumlah'        => $input['jumlah'],
        ':satuan'        => $input['satuan'],
        ':harga'         => $input['harga'],
        ':tanggal_masuk' => $input['tanggal_masuk'],
        ':deskripsi'     => isset($input['deskripsi']) ? $input['deskripsi'] : '',
        ':foto'          => isset($input['foto']) ? $input['foto'] : '' 
    ]);

    if ($sukses) {
        http_response_code(201);
        echo json_encode(["status" => "berhasil", "message" => "Semua data barang berhasil ditambahkan!"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "gagal", "message" => "Gagal menyimpan ke database"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "gagal", "message" => "Data wajib ada yang kosong! Cek nama, kategori, jumlah, satuan, harga, atau tanggal."]);
}
?>