<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/database.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!empty($input['id'])) {
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id = :id");
    $sukses = $stmt->execute([':id' => $input['id']]);

    if ($sukses) {
        echo json_encode(["status" => "berhasil", "message" => "Data berhasil dihapus"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "gagal", "message" => "Gagal menghapus data dari database"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "gagal", "message" => "ID tidak ditemukan"]);
}
?>