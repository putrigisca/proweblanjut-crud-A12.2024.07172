<?php
class UserModel {
    private $pdo; 
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>