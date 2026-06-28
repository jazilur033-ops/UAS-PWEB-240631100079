<?php
/**
 * Format angka ke format Rupiah
 */
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Hitung saldo (total pemasukan - total pengeluaran)
 */
function getSaldo($conn)
{
    $queryPemasukan = "SELECT SUM(jumlah) AS total FROM transaksi WHERE jenis = 'Pemasukan'";
    $resultPemasukan = mysqli_query($conn, $queryPemasukan);
    $pemasukan = mysqli_fetch_assoc($resultPemasukan)['total'] ?? 0;

    $queryPengeluaran = "SELECT SUM(jumlah) AS total FROM transaksi WHERE jenis = 'Pengeluaran'";
    $resultPengeluaran = mysqli_query($conn, $queryPengeluaran);
    $pengeluaran = mysqli_fetch_assoc($resultPengeluaran)['total'] ?? 0;

    return $pemasukan - $pengeluaran;
}

/**
 * Ambil total pemasukan
 */
function getTotalPemasukan($conn)
{
    $query = "SELECT SUM(jumlah) AS total FROM transaksi WHERE jenis = 'Pemasukan'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total'] ?? 0;
}

/**
 * Ambil total pengeluaran
 */
function getTotalPengeluaran($conn)
{
    $query = "SELECT SUM(jumlah) AS total FROM transaksi WHERE jenis = 'Pengeluaran'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total'] ?? 0;
}

/**
 * Ambil 5 transaksi terbaru
 */
function getTransaksiTerbaru($conn, $limit = 5)
{
    $query = "SELECT * FROM transaksi ORDER BY tanggal DESC LIMIT $limit";
    $result = mysqli_query($conn, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}
?>
