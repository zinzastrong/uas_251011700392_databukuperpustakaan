<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}
// Cek role user
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=dashboard">📚 Perpustakaan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php?page=buku">Data Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=report">Laporan</a></li>
            </ul>
            <span class="navbar-text me-3">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Pengguna') ?> (<?= htmlspecialchars($_SESSION['role'] ?? 'user') ?>)</span>
            <a href="index.php?page=login&action=logout" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="page-wrap">
    <div class="page-header">
        <h2>Manajemen Buku</h2>
        <?php if ($isAdmin): ?>
            <a href="index.php?page=buku&action=create" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah Buku</a>
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <div class="filter-bar">
        <form method="GET" action="index.php" class="row g-3">
            <input type="hidden" name="page" value="buku">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari judul, pengarang, penerbit, kategori..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
            <div class="col-md-2">
                <a href="index.php?page=buku" class="btn btn-secondary w-100"><i class="bi bi-arrow-repeat"></i> Reset</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Kategori</th>
                    <th>Cover</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($data) > 0): foreach ($data as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['id']) ?></td>
                    <td><?= htmlspecialchars($b['judul']) ?></td>
                    <td><?= htmlspecialchars($b['pengarang']) ?></td>
                    <td><?= htmlspecialchars($b['penerbit']) ?></td>
                    <td><?= $b['tahun_terbit'] ?></td>
                    <td><?= htmlspecialchars($b['kategori']) ?></td>
                    <td><?= !empty($b['cover_file']) ? '<img src="uploads/'.$b['cover_file'].'" width="50" height="70" style="object-fit:cover;">' : '<span class="text-muted">-</span>' ?></td>
                    <td>
                        <?php if ($isAdmin): ?>
                            <a href="index.php?page=buku&action=edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="index.php?page=buku&action=delete&id=<?= $b['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i></a>
                        <?php else: ?>
                            <span class="text-muted">Read Only</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" class="text-center">Belum ada data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
