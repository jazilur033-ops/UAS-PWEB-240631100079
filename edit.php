<?php
require_once 'config/koneksi.php';
require_once 'functions/functions.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: daftar.php');
    exit;
}

$query = "SELECT * FROM transaksi WHERE id_transaksi = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header('Location: daftar.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal   = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $jenis     = mysqli_real_escape_string($conn, $_POST['jenis']);
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $jumlah    = (float) $_POST['jumlah'];

    if (empty($tanggal) || empty($jenis) || empty($kategori) || $jumlah <= 0) {
        $error = 'Semua field wajib diisi dan jumlah harus lebih dari 0.';
    } else {
        $update = "UPDATE transaksi SET
                    tanggal = '$tanggal',
                    jenis = '$jenis',
                    kategori = '$kategori',
                    keterangan = '$keterangan',
                    jumlah = $jumlah
                   WHERE id_transaksi = $id";
        if (mysqli_query($conn, $update)) {
            $success = 'Transaksi berhasil diperbarui!';
            $result = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi = $id");
            $data = mysqli_fetch_assoc($result);
        } else {
            $error = 'Gagal memperbarui: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Transaksi | Sistem Catatan Keuangan</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="assets/img/logo.svg" alt="Logo" />
            <div>
                <h2>Keuangan<span>Ku</span></h2>
                <span>Catatan Keuangan</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php"><span class="icon">📊</span> Dashboard</a>
            <a href="tambah.php"><span class="icon">➕</span> Tambah Data</a>
            <a href="daftar.php"><span class="icon">📋</span> Daftar Data</a>
        </nav>
        <div class="sidebar-footer">© 2026 · UAS PWEB</div>
    </aside>

    <main class="main-content">
        <header class="navbar">
            <div class="navbar-left">
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">☰</button>
                <div>
                    <h3>Edit Transaksi</h3>
                    <p>Ubah data transaksi yang sudah tersimpan</p>
                </div>
            </div>
            <div class="navbar-right">
                <div class="user-badge">
                    <span class="avatar">A</span>
                    <span>Admin</span>
                </div>
            </div>
        </header>

        <section class="page-content">
            <div class="form-card">
                <h3>✏️ Edit Transaksi</h3>
                <p class="subtitle">Perbarui data transaksi #<?= $id ?></p>

                <?php if ($success): ?>
                    <div class="alert alert-success">✅ <?= $success ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger">❌ <?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label>Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required />
                    </div>
                    <div class="form-group">
                        <label>Jenis Transaksi <span class="required">*</span></label>
                        <select name="jenis" required>
                            <option value="Pemasukan" <?= $data['jenis'] === 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                            <option value="Pengeluaran" <?= $data['jenis'] === 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori <span class="required">*</span></label>
                        <input type="text" name="kategori" value="<?= htmlspecialchars($data['kategori']) ?>" required />
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan"><?= htmlspecialchars($data['keterangan'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Jumlah (Rp) <span class="required">*</span></label>
                        <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" min="1" step="1" required />
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Perbarui</button>
                        <a href="daftar.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('menuToggle');
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    </script>
</body>
</html>