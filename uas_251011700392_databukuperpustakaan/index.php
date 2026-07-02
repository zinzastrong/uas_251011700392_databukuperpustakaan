<?php
session_start();

// Include semua file yang diperlukan
require_once 'config/database.php';
require_once 'model/User.php';
require_once 'model/Buku.php';
require_once 'controller/AuthController.php';
require_once 'controller/BukuController.php';
require_once 'controller/ReportController.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($page) {
    case 'login':
        $controller = new AuthController();
        if ($action == 'login') {
            $controller->login();
        } elseif ($action == 'logout') {
            $controller->logout();
        } else {
            $controller->loginForm();
        }
        break;

    case 'register':
        $controller = new AuthController();
        if ($action == 'register') {
            $controller->register();
        } else {
            $controller->registerForm();
        }
        break;

    case 'dashboard':
        include 'view/dashboard.php';
        break;

    case 'buku':
        $controller = new BukuController();
        if ($action == 'create') {
            $controller->create();
        } elseif ($action == 'store') {
            $controller->store();
        } elseif ($action == 'edit') {
            $controller->edit();
        } elseif ($action == 'update') {
            $controller->update();
        } elseif ($action == 'delete') {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;

    case 'report':
        $controller = new ReportController();
        if ($action == 'pdf') {
            $controller->generatePDF();
        } elseif ($action == 'excel') {
            $controller->generateExcel();
        } else {
            $controller->index();
        }
        break;

    default:
        header("Location: index.php?page=login");
        exit();
}
?>