<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku - Perpustakaan</title>
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
                <li class="nav-item"><a class="nav-link" href="index.php?page=buku">Data Buku</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php?page=report">Laporan</a></li>
            </ul>
            <span class="navbar-text me-3">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Pengguna') ?> (<?= htmlspecialchars($_SESSION['role'] ?? 'user') ?>)</span>
            <a href="index.php?page=login&action=logout" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="page-wrap">
    <div class="page-header">
        <h2>Laporan Data Buku</h2>
    </div>
    <div class="filter-bar">
        <form method="GET" action="index.php" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="report">
            <div class="col-md-4">
                <label class="form-label">Filter Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori </option>
                    <?php foreach ($kategoriList as $kat): ?>
                        <option value="<?= htmlspecialchars($kat) ?>" <?= (isset($_GET['kategori']) && $_GET['kategori'] == $kat) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="index.php?page=report" class="btn btn-secondary w-100"><i class="bi bi-arrow-repeat"></i> Reset</a>
            </div>
        </form>
    </div>
    <div class="mb-3">
        <a href="index.php?page=report&action=pdf<?= isset($_GET['kategori']) ? '&kategori='.urlencode($_GET['kategori']) : '' ?>" class="btn btn-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
        <a href="index.php?page=report&action=excel<?= isset($_GET['kategori']) ? '&kategori='.urlencode($_GET['kategori']) : '' ?>" class="btn btn-success"><i class="bi bi-file-excel"></i> Export Excel</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr><th>ID</th><th>Judul</th><th>Pengarang</th><th>Penerbit</th><th>Tahun</th><th>ISBN</th><th>Halaman</th><th>Kategori</th><th>Sinopsis</th></tr>
            </thead>
            <tbody>
                <?php if (count($data) > 0): foreach ($data as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['id']) ?></td>
                    <td><?= htmlspecialchars($b['judul']) ?></td>
                    <td><?= htmlspecialchars($b['pengarang']) ?></td>
                    <td><?= htmlspecialchars($b['penerbit']) ?></td>
                    <td><?= $b['tahun_terbit'] ?></td>
                    <td><?= htmlspecialchars($b['isbn']) ?></td>
                    <td><?= $b['jumlah_halaman'] ?></td>
                    <td><?= htmlspecialchars($b['kategori']) ?></td>
                    <td><?= htmlspecialchars($b['sinopsis']) ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="9" class="text-center">Tidak ada data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
