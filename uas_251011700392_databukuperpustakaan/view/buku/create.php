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
    <title>Tambah Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="page-wrap" style="max-width: 800px;">
    <div class="page-header">
        <h2>Tambah Buku Baru</h2>
    </div>
    <div class="form-panel">
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger">
                <ul class="mb-0"><?php foreach ($_SESSION['errors'] as $e) echo "<li>$e</li>"; unset($_SESSION['errors']); ?></ul>
            </div>
        <?php endif; ?>
        <form method="POST" action="index.php?page=buku&action=store" enctype="multipart/form-data">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">ID Buku *</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="id" value="<?= htmlspecialchars($_SESSION['old']['id'] ?? '') ?>" required>
                    <small class="text-muted">Contoh: B001, atau isi dengan NIM.</small>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Judul *</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="judul" value="<?= htmlspecialchars($_SESSION['old']['judul'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Pengarang *</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="pengarang" value="<?= htmlspecialchars($_SESSION['old']['pengarang'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Penerbit *</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="penerbit" value="<?= htmlspecialchars($_SESSION['old']['penerbit'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Tahun Terbit *</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" name="tahun" value="<?= htmlspecialchars($_SESSION['old']['tahun'] ?? date('Y')) ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">ISBN *</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="isbn" value="<?= htmlspecialchars($_SESSION['old']['isbn'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Jumlah Halaman *</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" name="halaman" value="<?= htmlspecialchars($_SESSION['old']['halaman'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Kategori *</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="kategori" value="<?= htmlspecialchars($_SESSION['old']['kategori'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Sinopsis</label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="sinopsis" rows="3"><?= htmlspecialchars($_SESSION['old']['sinopsis'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Cover (opsional)</label>
                <div class="col-sm-9">
                    <input type="file" class="form-control" name="cover" accept="image/*">
                    <small class="text-muted">Max 2MB, format JPG, PNG, GIF</small>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                    <a href="index.php?page=buku" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
        <?php unset($_SESSION['old']); ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
