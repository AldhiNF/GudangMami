<?php
// 1. Panggil koneksi database
include 'koneksi.php';

// 2. Cek apakah ada parameter 'id' yang dikirim lewat URL
if (isset($_GET['id'])) {
    $id_barang = $_GET['id'];

    // 3. Siapkan query untuk menghapus data berdasarkan ID
    $query_hapus = "DELETE FROM barang WHERE id_barang = '$id_barang'";
    
    // 4. Eksekusi query-nya
    $hapus = mysqli_query($koneksi, $query_hapus);

    // 5. Cek apakah proses hapus berhasil
    if ($hapus) {
        echo "<script>
                alert('Data barang berhasil dihapus dari gudang!');
                window.location='inti.php';
              </script>";
    } else {
        echo "<script>
                alert('Waduh, gagal menghapus data!');
                window.location='inti.php';
              </script>";
    }
} else {
    // Kalau nggak ada ID yang dikirim (misal user iseng ngetik URL langsung), 
    // langsung tendang balik ke halaman inti
    header("Location: inti.php");
    exit;
}
?>