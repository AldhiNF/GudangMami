<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'pesan' => 'Belum login']);
    exit;
}

include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['status' => 'error', 'pesan' => 'ID barang tidak valid']);
    exit;
}

$id_barang = (int) $_GET['id'];

$query = "SELECT * FROM barang WHERE id_barang = '$id_barang'";
$hasil = mysqli_query($koneksi, $query);

if ($hasil && mysqli_num_rows($hasil) > 0) {
    $data = mysqli_fetch_assoc($hasil);
    echo json_encode([
        'status'        => 'ok',
        'id_barang'     => $data['id_barang'],
        'nama_barang'   => $data['nama_barang'],
        'kategori'      => $data['kategori'],
        'stok_sekarang' => $data['stok_sekarang']
    ]);
} else {
    echo json_encode(['status' => 'not_found', 'pesan' => 'Barang dengan kode ini tidak ditemukan di database.']);
}
?>