<?php
require_once 'config/koneksi.php';
require_once 'functions/functions.php';

$totalPemasukan = getTotalPemasukan($conn);
$totalPengeluaran = getTotalPengeluaran($conn);
$saldo = getSaldo($conn);
$transaksiTerbaru = getTransaksiTerbaru($conn, 5);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Sistem Catatan Keuangan</title>
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
            <a href="index.php" class="active">
                <span class="icon">📊</span> Dashboard
            </a>
            <a href="tambah.php">
                <span class="icon">➕</span> Tambah Data
            </a>
            <a href="daftar.php">
                <span class="icon">📋</span> Daftar Data
            </a>
        </nav>
        <div class="sidebar-footer">© 2026 · UAS PWEB</div>
    </aside>

    <main class="main-content">
        <header class="navbar">
            <div class="navbar-left">
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">☰</button>
                <div>
                    <h3>Dashboard</h3>
                    <p>Ringkasan keuangan Anda</p>
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
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="label"><span class="icon-big">💰</span> Total Pemasukan</div>
                    <div class="value green"><?= formatRupiah($totalPemasukan) ?></div>
                    <div class="sub">Semua pemasukan</div>
                </div>
                <div class="stat-card">
                    <div class="label"><span class="icon-big">💸</span> Total Pengeluaran</div>
                    <div class="value red"><?= formatRupiah($totalPengeluaran) ?></div>
                    <div class="sub">Semua pengeluaran</div>
                </div>
                <div class="stat-card">
                    <div class="label"><span class="icon-big">🏦</span> Saldo Akhir</div>
                    <div class="value blue"><?= formatRupiah($saldo) ?></div>
                    <div class="sub">Pemasukan − Pengeluaran</div>
                </div>
                <div class="stat-card">
                    <div class="label"><span class="icon-big">📈</span> Transaksi</div>
                    <div class="value purple" style="font-size:24px;"><?= count($transaksiTerbaru) ?> terbaru</div>
                    <div class="sub">5 transaksi terakhir</div>
                </div>
            </div>

            <div class="table-wrapper">
                <div class="table-header">
                    <h4>🕐 Transaksi Terbaru</h4>
                    <a href="tambah.php" class="btn-add">+ Tambah</a>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($transaksiTerbaru) > 0): ?>
                                <?php foreach ($transaksiTerbaru as $trx): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($trx['tanggal'])) ?></td>
                                        <td>
                                            <span class="badge <?= $trx['jenis'] === 'Pemasukan' ? 'badge-pemasukan' : 'badge-pengeluaran' ?>">
                                                <?= $trx['jenis'] ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($trx['kategori']) ?></td>
                                        <td><?= htmlspecialchars($trx['keterangan'] ?? '-') ?></td>
                                        <td><?= formatRupiah($trx['jumlah']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" style="padding:30px; text-align:center; color:#94a3b8;">Belum ada transaksi.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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