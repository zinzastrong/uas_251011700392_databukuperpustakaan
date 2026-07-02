<?php
// model/User.php

class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($username, $password, $nama_lengkap, $role = 'user') {
        $hashed = md5($password);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, nama_lengkap, role) 
                                      VALUES (:username, :password, :nama_lengkap, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':nama_lengkap', $nama_lengkap);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    public function isUsernameExists($username) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>