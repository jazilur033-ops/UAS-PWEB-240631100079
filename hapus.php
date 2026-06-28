<?php
require_once 'config/koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id > 0) {
    $query = "DELETE FROM transaksi WHERE id_transaksi = $id";
    mysqli_query($conn, $query);
}
header('Location: daftar.php');
exit;
?>