<?php
class BarangModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM barang ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($keyword) {
        $stmt = $this->pdo->prepare("SELECT * FROM barang WHERE kode_barang LIKE :keyword OR nama_barang LIKE :keyword OR kategori LIKE :keyword ORDER BY id DESC");
        $stmt->bindValue(':keyword', "%$keyword%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaxKode() {
        $query = $this->pdo->query("SELECT MAX(SUBSTRING(kode_barang, 5)) as max_kode FROM barang");
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahData($data) {
        $sql = "INSERT INTO barang (kode_barang, foto, nama_barang, warna, kategori, deskripsi, jumlah, satuan, harga, tanggal_masuk) 
                VALUES (:kode_barang, :foto, :nama_barang, :warna, :kategori, :deskripsi, :jumlah, :satuan, :harga, :tanggal_masuk)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':kode_barang'   => $data['kode_barang'],
            ':foto'          => $data['foto'],
            ':nama_barang'   => $data['nama_barang'],
            ':warna'         => $data['warna'],
            ':kategori'      => $data['kategori'],
            ':deskripsi'     => $data['deskripsi'],
            ':jumlah'        => $data['jumlah'],
            ':satuan'        => $data['satuan'],
            ':harga'         => $data['harga'],
            ':tanggal_masuk' => $data['tanggal_masuk']
        ]);
        return $stmt->rowCount();
    }

    public function getBarangById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM barang WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hapusData($id) {
        $stmt = $this->pdo->prepare("DELETE FROM barang WHERE id = ?");
        return $stmt->execute([$id]);
    }
}       
?>