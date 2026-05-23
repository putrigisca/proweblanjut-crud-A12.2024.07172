<?php
class Barang {
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
}
?>