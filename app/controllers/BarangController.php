<?php
require_once '../config/database.php';
require_once '../app/models/BarangModel.php';

class BarangController {
    private $model;

    public function __construct() {
        global $pdo;
        $this->model = new BarangModel($pdo);
    }

    public function index() {
        session_start(); 
        global $pdo;

        if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
            if (isset($_COOKIE["username"])) {
                $stmt_cookie = $pdo->prepare("SELECT * FROM users WHERE username = :username");
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

    public function create() {
        $dataModel = $this->model->getMaxKode();
        $next = ($dataModel['max_kode'] ?? 0) + 1;
        $kode_otomatis = "BRG-" . str_pad($next, 3, '0', STR_PAD_LEFT);
        
        $errors = [];
        require_once '../app/views/barang/create.php';
    }

    public function store() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $nama_foto_baru = '';

            $data = [
                'kode_barang'   => htmlspecialchars($_POST['kode_barang']),
                'nama_barang'   => htmlspecialchars($_POST['nama_barang']),
                'warna'         => htmlspecialchars($_POST['warna']),
                'kategori'      => htmlspecialchars($_POST['kategori']),
                'deskripsi'     => htmlspecialchars($_POST['deskripsi']),
                'jumlah'        => htmlspecialchars($_POST['jumlah']),
                'satuan'        => htmlspecialchars($_POST['satuan']),
                'harga'         => htmlspecialchars($_POST['harga']),
                'tanggal_masuk' => htmlspecialchars($_POST['tanggal_masuk']),
                'foto'          => '' 
            ];
            if (empty(trim($data['nama_barang']))) {
                $errors[] = "Nama barang tidak boleh kosong.";
            }
            if (!is_numeric($data['jumlah']) || !is_numeric($data['harga'])) {
                $errors[] = "Jumlah dan Harga harus berupa angka.";
            }

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                $fileName = $_FILES['foto']['name'];
                $fileSize = $_FILES['foto']['size'];
                $tmpName  = $_FILES['foto']['tmp_name'];

                $validExtensions = ['jpg', 'jpeg', 'png'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExt, $validExtensions)) {
                    $errors[] = "Gagal: Ekstensi file hanya boleh JPG, JPEG, atau PNG.";
                } elseif ($fileSize > 1000000) { 
                    $errors[] = "Gagal Upload Foto: Ukuran foto maksimal 1 MB.";
                } else {
                    $nama_foto_baru = uniqid() . '.' . $fileExt; 
                }
            } else {
                $errors[] = "Gagal: Foto barang wajib diunggah.";
            }

            if (empty($errors)) {
                try {
                    move_uploaded_file($tmpName, '../assets/uploads/' . $nama_foto_baru);
                    $data['foto'] = $nama_foto_baru;
                    
                    $this->model->tambahData($data);
                    
                    header("Location: index.php");
                    exit;
                } catch (PDOException $e) {
                    $errors[] = "Gagal menambah data: " . $e->getMessage();
                }
            }

            $kode_otomatis = $data['kode_barang']; 
            require_once '../app/views/barang/create.php';
        }
    }
    
    public function hapus() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $barang = $this->model->getBarangById($id);

            if ($barang && !empty($barang['foto'])) {

                $file_path = '../assets/uploads/' . $barang['foto'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            $this->model->hapusData($id);
        }

        header("Location: index.php");
        exit;
    }
    
    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: index.php");
            exit;
        }

        $id = $_GET['id'];
        $barang = $this->model->getBarangById($id);

        if (!$barang) {
            die("Data tidak ditemukan!");
        }

        $errors = [];
        require_once '../app/views/barang/edit.php';
    }

    public function update() {
        if (!isset($_GET['id'])) {
            header("Location: index.php");
            exit;
        }

        $id = $_GET['id'];
        $barang = $this->model->getBarangById($id);

        if (!$barang) {
            die("Data tidak ditemukan!");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode_barang   = $_POST['kode_barang'];
            $nama_barang   = $_POST['nama_barang'];
            $warna         = $_POST['warna'];
            $kategori      = $_POST['kategori'];
            $deskripsi     = $_POST['deskripsi'];
            $jumlah        = $_POST['jumlah'];
            $satuan        = $_POST['satuan'];
            $harga         = $_POST['harga'];
            $tanggal_masuk = $_POST['tanggal_masuk'];

            $errors = [];
            if (empty(trim($nama_barang))) {
                $errors[] = "Nama barang tidak boleh kosong.";
            }
            if (!is_numeric($jumlah) || !is_numeric($harga)) {
                $errors[] = "Jumlah dan Harga harus berupa angka.";
            }

            $nama_foto_baru = $barang['foto'];
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                $fileName = $_FILES['foto']['name'];
                $fileSize = $_FILES['foto']['size'];
                $tmpName  = $_FILES['foto']['tmp_name'];

                $validExtensions = ['jpg', 'jpeg', 'png'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExt, $validExtensions)) {
                    $errors[] = "Gagal: Ekstensi file hanya boleh JPG, JPEG, atau PNG.";
                } elseif ($fileSize > 1000000) {
                    $errors[] = "Gagal Upload Foto: Ukuran foto maksimal 1 MB.";
                } else {
                    $nama_foto_baru = uniqid() . '.' . $fileExt; 
                    $tmp_file_path = $tmpName;
                }
            }

            if (empty($errors)) {
                try {
                    if (isset($tmp_file_path)) {
                        if (!empty($barang['foto']) && file_exists('../assets/uploads/' . $barang['foto'])) {
                            unlink('../assets/uploads/' . $barang['foto']);
                        }
                        move_uploaded_file($tmp_file_path, '../assets/uploads/' . $nama_foto_baru);
                    }

                    $dataUpdate = [
                        ':kode_barang'   => $kode_barang,
                        ':foto'           => $nama_foto_baru,
                        ':nama_barang'   => $nama_barang,
                        ':warna'         => $warna,
                        ':kategori'      => $kategori,
                        ':deskripsi'     => $deskripsi,
                        ':jumlah'        => $jumlah,
                        ':satuan'        => $satuan,
                        ':harga'         => $harga,
                        ':tanggal_masuk' => $tanggal_masuk,
                        ':id'            => $id
                    ];

                    $this->model->updateData($dataUpdate);

                    header("Location: index.php");
                    exit;
                } catch (PDOException $e) {
                    $errors[] = "Gagal mengupdate data: " . $e->getMessage();
                }
            }   
            
            require_once '../app/views/barang/edit.php';
        }
    }
}

?>