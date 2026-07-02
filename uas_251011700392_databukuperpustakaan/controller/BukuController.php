<?php
// controller/BukuController.php

class BukuController {
    private $bukuModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $this->bukuModel = new Buku();
    }

    // === SEMUA USER BISA AKSES (READ ONLY) ===
    public function index() {
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $data = $keyword ? $this->bukuModel->search($keyword) : $this->bukuModel->getAll();
        include 'view/buku/index.php';
    }

    // === KHUSUS ADMIN SAJA (CREATE, UPDATE, DELETE) ===
    private function checkAdmin() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak. Hanya admin yang diizinkan.';
            header("Location: index.php?page=buku");
            exit();
        }
    }

    public function create() {
        $this->checkAdmin();
        include 'view/buku/create.php';
    }

    public function store() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=buku");
            exit();
        }
        // ... sisanya sama seperti sebelumnya
        $errors = $this->validate($_POST, $_FILES, true);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?page=buku&action=create");
            exit();
        }
        $cover = $this->uploadFile($_FILES['cover']);
        $data = [
            'id' => $_POST['id'],
            'judul' => $_POST['judul'],
            'pengarang' => $_POST['pengarang'],
            'penerbit' => $_POST['penerbit'],
            'tahun' => $_POST['tahun'],
            'isbn' => $_POST['isbn'],
            'halaman' => $_POST['halaman'],
            'kategori' => $_POST['kategori'],
            'sinopsis' => $_POST['sinopsis'],
            'cover' => $cover
        ];
        if ($this->bukuModel->create($data)) {
            $_SESSION['success'] = "Buku berhasil ditambahkan.";
            header("Location: index.php?page=buku");
        } else {
            $_SESSION['error'] = "Gagal menambahkan buku.";
            header("Location: index.php?page=buku&action=create");
        }
        exit();
    }

    public function edit() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? '';
        if (!$id) {
            header("Location: index.php?page=buku");
            exit();
        }
        $buku = $this->bukuModel->getById($id);
        if (!$buku) {
            $_SESSION['error'] = "Data tidak ditemukan.";
            header("Location: index.php?page=buku");
            exit();
        }
        include 'view/buku/edit.php';
    }

    public function update() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=buku");
            exit();
        }
        $id = $_POST['id'];
        $errors = $this->validate($_POST, $_FILES, false);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?page=buku&action=edit&id=$id");
            exit();
        }
        $cover = '';
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
            $cover = $this->uploadFile($_FILES['cover']);
            $old = $this->bukuModel->getById($id);
            if ($old && !empty($old['cover_file'])) {
                $oldFile = 'uploads/' . $old['cover_file'];
                if (file_exists($oldFile)) unlink($oldFile);
            }
        }
        $data = [
            'judul' => $_POST['judul'],
            'pengarang' => $_POST['pengarang'],
            'penerbit' => $_POST['penerbit'],
            'tahun' => $_POST['tahun'],
            'isbn' => $_POST['isbn'],
            'halaman' => $_POST['halaman'],
            'kategori' => $_POST['kategori'],
            'sinopsis' => $_POST['sinopsis'],
            'cover' => $cover
        ];
        if ($this->bukuModel->update($id, $data)) {
            $_SESSION['success'] = "Buku berhasil diperbarui.";
            header("Location: index.php?page=buku");
        } else {
            $_SESSION['error'] = "Gagal memperbarui buku.";
            header("Location: index.php?page=buku&action=edit&id=$id");
        }
        exit();
    }

    public function delete() {
        $this->checkAdmin();
        $id = $_GET['id'] ?? '';
        if ($id && $this->bukuModel->delete($id)) {
            $_SESSION['success'] = "Buku berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus buku.";
        }
        header("Location: index.php?page=buku");
        exit();
    }

    // === FUNGSI VALIDASI DAN UPLOAD (TIDAK BERUBAH) ===
    private function validate($post, $files, $checkId = true) {
        $errors = [];
        if ($checkId && (empty($post['id']) || $this->bukuModel->isIdExists($post['id']))) {
            $errors[] = "ID buku harus diisi dan unik.";
        }
        if (empty($post['judul'])) $errors[] = "Judul harus diisi.";
        if (empty($post['pengarang'])) $errors[] = "Pengarang harus diisi.";
        if (empty($post['penerbit'])) $errors[] = "Penerbit harus diisi.";
        if (empty($post['tahun']) || !is_numeric($post['tahun']) || $post['tahun'] < 1900 || $post['tahun'] > date('Y')) {
            $errors[] = "Tahun terbit harus diisi (1900-" . date('Y') . ").";
        }
        if (empty($post['isbn'])) $errors[] = "ISBN harus diisi.";
        if (empty($post['halaman']) || !is_numeric($post['halaman']) || $post['halaman'] < 1) {
            $errors[] = "Jumlah halaman harus diisi dan angka positif.";
        }
        if (empty($post['kategori'])) $errors[] = "Kategori harus diisi.";
        if (isset($files['cover']) && $files['cover']['error'] === 0) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($files['cover']['type'], $allowed)) {
                $errors[] = "Cover harus gambar (JPG, PNG, GIF).";
            }
            if ($files['cover']['size'] > 2 * 1024 * 1024) {
                $errors[] = "Ukuran cover maksimal 2MB.";
            }
        }
        return $errors;
    }

    private function uploadFile($file) {
        $targetDir = 'uploads/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $targetDir . $filename);
        return $filename;
    }
}
?>
