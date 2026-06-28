<?php
require_once 'config/koneksi.php';
require_once 'functions/functions.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$jenis_filter = isset($_GET['jenis']) ? $_GET['jenis'] : '';

$where = [];
if (!empty($search)) {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $where[] = "(kategori LIKE '%$search_escaped%' OR keterangan LIKE '%$search_escaped%')";
}
if (!empty($jenis_filter) && in_array($jenis_filter, ['Pemasukan', 'Pengeluaran'])) {
    $jenis_escaped = mysqli_real_escape_string($conn, $jenis_filter);
    $where[] = "jenis = '$jenis_escaped'";
}
$where_clause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';
$query = "SELECT * FROM transaksi $where_clause ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Transaksi | Sistem Catatan Keuangan</title>
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
            <a href="daftar.php" class="active"><span class="icon">📋</span> Daftar Data</a>
        </nav>
        <div class="sidebar-footer">© 2026 · UAS PWEB</div>
    </aside>

    <main class="main-content">
        <header class="navbar">
            <div class="navbar-left">
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">☰</button>
                <div>
                    <h3>Daftar Transaksi</h3>
                    <p>Semua data transaksi keuangan</p>
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
            <div class="table-wrapper">
                <div class="table-header">
                    <h4>📋 Semua Transaksi</h4>
                    <a href="tambah.php" class="btn-add">+ Tambah</a>
                </div>

                <div class="filter-bar">
                    <form method="GET" action="" class="filter-form">
                        <input type="text" name="search" placeholder="🔍 Cari kategori atau keterangan..." value="<?= htmlspecialchars($search) ?>" />
                        <select name="jenis">
                            <option value="">Semua Jenis</option>
                            <option value="Pemasukan" <?= $jenis_filter === 'Pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                            <option value="Pengeluaran" <?= $jenis_filter === 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                        </select>
                        <button type="submit">Cari</button>
                        <?php if (!empty($search) || !empty($jenis_filter)): ?>
                            <a href="daftar.php" class="reset-link">↺ Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                        <td>
                                            <span class="badge <?= $row['jenis'] === 'Pemasukan' ? 'badge-pemasukan' : 'badge-pengeluaran' ?>">
                                                <?= $row['jenis'] ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                                        <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                                        <td><?= formatRupiah($row['jumlah']) ?></td>
                                        <td>
                                            <div class="actions">
                                                <a href="edit.php?id=<?= $row['id_transaksi'] ?>" class="btn-edit">✏️ Edit</a>
                                                <a href="hapus.php?id=<?= $row['id_transaksi'] ?>" class="btn-delete" onclick="return confirm('Yakin hapus transaksi ini?')">🗑️ Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7" style="padding:30px; text-align:center; color:#94a3b8;">Tidak ada transaksi yang cocok.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    Menampilkan <?= mysqli_num_rows($result) ?> transaksi
                    <?php if (!empty($search) || !empty($jenis_filter)): ?>
                        (hasil pencarian)
                    <?php endif; ?>
                </div>
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