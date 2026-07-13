<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    
    $nama_barang = ucwords(strtolower($_POST['nama_barang']));
    $kategori      = $_POST['kategori']; 
    
    // Pastikan yang ditangkap benar-benar diubah jadi angka murni (Integer)
    $stok_sekarang = (int) $_POST['stok_sekarang'];

    // (Opsional) Validasi tambahan, tolak kalau hasilnya kurang dari 0
    if ($stok_sekarang < 0) {
        echo "<script>alert('Gagal! Stok tidak boleh minus.'); window.history.back();</script>";
        exit; // Hentikan proses penyimpanan
    }

    $query = "INSERT INTO barang (nama_barang, kategori, stok_sekarang) 
              VALUES ('$nama_barang', '$kategori', '$stok_sekarang')";
    
    $simpan = mysqli_query($koneksi, $query);

    if ($simpan) {
        echo "<script>alert('Sip! Data barang berhasil ditambahkan.'); window.location='inti.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Tambah Stok Gudang
                </div>
                <div class="card-body">
                    
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" required placeholder="Contoh: Taro Net Seaweed">
                        </div>
                        
                        <!-- Ini bagian Dropdown Kategorinya -->
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Cemilan">Cemilan</option>
                                <option value="Minuman">Minuman</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Stok Awal</label>
                            <input type="number" name="stok_sekarang" class="form-control" required placeholder="Contoh: 100" min="0">
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="inti.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" name="submit" class="btn btn-success">Simpan Data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>