<?php
require_once '../config/database.php'; 

require_once '../app/controllers/BarangController.php';

$controller = new BarangController($pdo); 
$controller->index();
?>