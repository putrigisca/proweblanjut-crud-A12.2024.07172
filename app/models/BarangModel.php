<?php
class BarangModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM barang ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($keyword) {
        $stmt = $this->db->prepare("SELECT * FROM barang WHERE kode_barang LIKE :keyword OR nama_barang LIKE :keyword OR kategori LIKE :keyword ORDER BY id DESC");
        $stmt->bindValue(':keyword', "%$keyword%");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaxKode() {
        $query = $this->db->query("SELECT MAX(SUBSTRING(kode_barang, 5)) as max_kode FROM barang");
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function tambahData($data) {
        $sql = "INSERT INTO barang (kode_barang, foto, nama_barang, warna, kategori, deskripsi, jumlah, satuan, harga, tanggal_masuk) 
                VALUES (:kode_barang, :foto, :nama_barang, :warna, :kategori, :deskripsi, :jumlah, :satuan, :harga, :tanggal_masuk)";
        $stmt = $this->db->prepare($sql);
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
}       
?>