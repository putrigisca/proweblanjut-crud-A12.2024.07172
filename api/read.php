<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/database.php';

$stmt = $pdo->prepare("SELECT * FROM barang");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>