<?php
// config/database.php

class Database {
    private $host = 'localhost';
    private $dbname = 'db_perpustakaan';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, 
                                      $this->username, 
                                      $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->exec("SET NAMES utf8mb4");
            } catch (PDOException $e) {
                die("Koneksi gagal: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
?>