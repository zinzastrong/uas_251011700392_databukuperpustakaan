<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}
$bukuModel = new Buku();
$bukuList = $bukuModel->getAll();
$nama = $_SESSION['nama_lengkap'] ?? 'Pengguna';
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Perpustakaan</title>
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
                <li class="nav-item"><a class="nav-link active" href="index.php?page=dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=buku">Data Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=report">Laporan</a></li>
            </ul>
            <span class="navbar-text me-3">Halo, <?= htmlspecialchars($nama) ?> (<?= htmlspecialchars($role) ?>)</span>
            <a href="index.php?page=login&action=logout" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="page-wrap">
    <div class="page-header">
        <h2>Daftar Buku</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Cover</th>          <!-- Tambahkan kolom Cover -->
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($bukuList) > 0): ?>
                    <?php foreach ($bukuList as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['id']) ?></td>
                        <td>
                            
                            <?php if (!empty($b['cover_file'])): ?>
                                <img src="uploads/<?= $b['cover_file'] ?>" width="50" height="70" style="object-fit: cover; border-radius: 4px;" alt="cover">
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                            
                            
                        </td>
                        <td><?= htmlspecialchars($b['judul']) ?></td>
                        <td><?= htmlspecialchars($b['pengarang']) ?></td>
                        <td><?= htmlspecialchars($b['penerbit']) ?></td>
                        <td><?= $b['tahun_terbit'] ?></td>
                        <td><?= htmlspecialchars($b['kategori']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Belum ada data buku.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>