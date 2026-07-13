<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: inti.php");
    exit;
}

$id_barang = $_GET['id'];
$query_tampil = "SELECT * FROM barang WHERE id_barang = '$id_barang'";
$hasil = mysqli_query($koneksi, $query_tampil);
$data_lama = mysqli_fetch_assoc($hasil);

if (isset($_POST['update'])) {
    $nama_barang = ucwords(strtolower($_POST['nama_barang']));
    $kategori    = $_POST['kategori'];

    $query_update = "UPDATE barang SET 
                        nama_barang = '$nama_barang', 
                        kategori = '$kategori'
                     WHERE id_barang = '$id_barang'";
    
    $update = mysqli_query($koneksi, $query_update);

    if ($update) {
        echo "<script>alert('Profil barang berhasil diperbarui!'); window.location='inti.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark fw-bold">
                    Edit Profil Barang
                </div>
                <div class="card-body">
                    
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" required value="<?php echo $data_lama['nama_barang']; ?>">
                        </div>
                        
                        <!-- Dropdown Kategori dengan auto-select data lama -->
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="Makanan" <?php if($data_lama['kategori'] == 'Makanan') echo 'selected'; ?>>Makanan</option>
                                <option value="Cemilan" <?php if($data_lama['kategori'] == 'Cemilan') echo 'selected'; ?>>Cemilan</option>
                                <option value="Minuman" <?php if($data_lama['kategori'] == 'Minuman') echo 'selected'; ?>>Minuman</option>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="inti.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" name="update" class="btn btn-warning">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>