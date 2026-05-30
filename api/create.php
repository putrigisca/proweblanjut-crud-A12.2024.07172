<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/database.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!empty($input['nama_barang']) && !empty($input['kategori']) && isset($input['jumlah']) && !empty($input['satuan']) && isset($input['harga']) && !empty($input['tanggal_masuk'])) {
    
    $queryKode = "SELECT MAX(SUBSTRING(kode_barang, 5)) as max_kode FROM barang";
    $stmtKode = $pdo->query($queryKode);
    $dataKode = $stmtKode->fetch(PDO::FETCH_ASSOC);
    
    $max_kode = $dataKode['max_kode'];
    
    if ($max_kode) {
        $urutan = (int) $max_kode;
        $urutan++;
    } else {
        $urutan = 1;
    }
    
    $kode_barang_baru = "BRG-" . str_pad($urutan, 3, "0", STR_PAD_LEFT);
    
    $sql = "INSERT INTO barang (kode_barang, nama_barang, warna, kategori, jumlah, satuan, harga, tanggal_masuk, deskripsi, foto) 
            VALUES (:kode_barang, :nama_barang, :warna, :kategori, :jumlah, :satuan, :harga, :tanggal_masuk, :deskripsi, :foto)";
    
    $stmt = $pdo->prepare($sql);
    $sukses = $stmt->execute([
        ':kode_barang'   => $kode_barang_baru,
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
        echo json_encode(["status" => "berhasil", "message" => "Semua data barang berhasil ditambahkan dengan kode: " . $kode_barang_baru]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "gagal", "message" => "Gagal menyimpan ke database"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "gagal", "message" => "Data wajib ada yang kosong! Cek nama, kategori, jumlah, satuan, harga, atau tanggal."]);
}
?>