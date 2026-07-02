<?php
// controller/AuthController.php

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function loginForm() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?page=dashboard");
            exit();
        }
        include 'view/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=login");
            exit();
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username == '' || $password == '') {
            $_SESSION['error'] = 'Username dan password harus diisi.';
            header("Location: index.php?page=login");
            exit();
        }

        $user = $this->userModel->findByUsername($username);

        if ($user && $user['password'] === md5($password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];
            unset($_SESSION['error']);
            header("Location: index.php?page=dashboard");
            exit();
        } else {
            $_SESSION['error'] = 'Username atau password salah.';
            header("Location: index.php?page=login");
            exit();
        }
    }

    public function registerForm() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?page=dashboard");
            exit();
        }
        include 'view/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=register");
            exit();
        }

        if (isset($_SESSION['user_id'])) {
            session_destroy();
            session_start();
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
        $role = trim($_POST['role'] ?? 'user');

        $errors = [];
        if (empty($username)) $errors[] = 'Username harus diisi.';
        if (empty($password)) $errors[] = 'Password harus diisi.';
        if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';
        if (empty($nama_lengkap)) $errors[] = 'Nama lengkap harus diisi.';

        if ($this->userModel->isUsernameExists($username)) {
            $errors[] = 'Username sudah terdaftar.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?page=register");
            exit();
        }

        if ($this->userModel->create($username, $password, $nama_lengkap, $role)) {
            $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
            header("Location: index.php?page=login");
            exit();
        } else {
            $_SESSION['error'] = 'Registrasi gagal, coba lagi.';
            header("Location: index.php?page=register");
            exit();
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    }
}
?>