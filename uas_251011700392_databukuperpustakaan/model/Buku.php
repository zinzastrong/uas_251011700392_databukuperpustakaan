<?php
// model/Buku.php

class Buku {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM buku ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM buku WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO buku (id, judul, pengarang, penerbit, tahun_terbit, isbn, jumlah_halaman, kategori, sinopsis, cover_file) 
                VALUES (:id, :judul, :pengarang, :penerbit, :tahun, :isbn, :halaman, :kategori, :sinopsis, :cover)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':judul', $data['judul']);
        $stmt->bindParam(':pengarang', $data['pengarang']);
        $stmt->bindParam(':penerbit', $data['penerbit']);
        $stmt->bindParam(':tahun', $data['tahun']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':halaman', $data['halaman']);
        $stmt->bindParam(':kategori', $data['kategori']);
        $stmt->bindParam(':sinopsis', $data['sinopsis']);
        $stmt->bindParam(':cover', $data['cover']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $sql = "UPDATE buku SET 
                judul = :judul, 
                pengarang = :pengarang, 
                penerbit = :penerbit, 
                tahun_terbit = :tahun, 
                isbn = :isbn, 
                jumlah_halaman = :halaman, 
                kategori = :kategori, 
                sinopsis = :sinopsis";
        if (!empty($data['cover'])) {
            $sql .= ", cover_file = :cover";
        }
        $sql .= " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':judul', $data['judul']);
        $stmt->bindParam(':pengarang', $data['pengarang']);
        $stmt->bindParam(':penerbit', $data['penerbit']);
        $stmt->bindParam(':tahun', $data['tahun']);
        $stmt->bindParam(':isbn', $data['isbn']);
        $stmt->bindParam(':halaman', $data['halaman']);
        $stmt->bindParam(':kategori', $data['kategori']);
        $stmt->bindParam(':sinopsis', $data['sinopsis']);
        if (!empty($data['cover'])) {
            $stmt->bindParam(':cover', $data['cover']);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $buku = $this->getById($id);
        if ($buku && !empty($buku['cover_file'])) {
            $file = 'uploads/' . $buku['cover_file'];
            if (file_exists($file)) unlink($file);
        }
        $stmt = $this->conn->prepare("DELETE FROM buku WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function search($keyword) {
        $keyword = '%' . $keyword . '%';
        $sql = "SELECT * FROM buku WHERE 
                judul LIKE :k OR pengarang LIKE :k OR penerbit LIKE :k OR kategori LIKE :k 
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':k', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($kategori) {
        $stmt = $this->conn->prepare("SELECT * FROM buku WHERE kategori = :k ORDER BY created_at DESC");
        $stmt->bindParam(':k', $kategori);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories() {
        $stmt = $this->conn->query("SELECT DISTINCT kategori FROM buku ORDER BY kategori");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function isIdExists($id) {
        $stmt = $this->conn->prepare("SELECT id FROM buku WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>