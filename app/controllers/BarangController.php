<?php
require_once '../app/models/BarangModel.php';

class BarangController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Barang($db);
    }

    public function index() {
        session_start(); 
        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            if (isset($_COOKIE["username"])) {
                $stmt_cookie = $this->db->prepare("SELECT * FROM users WHERE username = :username");
                $stmt_cookie->execute([':username' => $_COOKIE["username"]]);
                $user_cookie = $stmt_cookie->fetch(PDO::FETCH_ASSOC);

                if ($user_cookie) {
                    $_SESSION['login'] = true;
                    $_SESSION['nama_lengkap'] = $user_cookie['nama_lengkap'];
                    $_SESSION['username'] = $user_cookie['username'];
                } else {
                    header("Location: ../login.php");
                    exit();
                }
            } else {
                header("Location: ../login.php");
                exit();
            }
        }

        $keyword = '';
        if (isset($_GET['cari'])) {
            $keyword = $_GET['cari'];
            $data = $this->model->search($keyword); 
        } else {
            $data = $this->model->getAll(); 
        }

        require_once '../app/views/barang/index.php';
    }
}
?>