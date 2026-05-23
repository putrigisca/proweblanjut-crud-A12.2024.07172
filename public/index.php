<?php
require_once '../config/database.php'; 

require_once '../app/controllers/BarangController.php';

$controller = new BarangController(); 
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    default:
        $controller->index();
        break;
}
?>