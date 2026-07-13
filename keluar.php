<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_POST['catat'])) {
    $id_barang = $_POST['id_barang'];
    // Validasi paksa jadi angka murni
    $jumlah_keluar = (int) $_POST['jumlah_keluar'];

    if ($jumlah_keluar <= 0) {
        echo "<script>alert('Gagal! Jumlah keluar harus lebih dari 0.'); window.history.back();</script>";
        exit;
    }

    // Cek sisa stok saat ini
    $query_cek = "SELECT nama_barang, stok_sekarang FROM barang WHERE id_barang = '$id_barang'";
    $hasil_cek = mysqli_query($koneksi, $query_cek);
    $data_cek = mysqli_fetch_assoc($hasil_cek);

    // Kalau jumlah keluar LEBIH BESAR dari stok sekarang
    if ($jumlah_keluar > $data_cek['stok_sekarang']) {
        echo "<script>
                alert('GAGAL! Stok tidak mencukupi.\\nSisa stok " . $data_cek['nama_barang'] . " saat ini hanya: " . $data_cek['stok_sekarang'] . "'); 
                window.history.back();
              </script>";
        exit;
    }

    // Kurangi stoknya
    $query_kurangi = "UPDATE barang SET stok_sekarang = stok_sekarang - $jumlah_keluar WHERE id_barang = '$id_barang'";
    $kurangi = mysqli_query($koneksi, $query_kurangi);

    if ($kurangi) {
        // ==========================================
        // FITUR BARU: CATAT KE TABEL RIWAYAT
        // ==========================================
        $query_riwayat = "INSERT INTO riwayat_keluar (id_barang, jumlah_keluar) VALUES ('$id_barang', '$jumlah_keluar')";
        mysqli_query($koneksi, $query_riwayat);

        echo "<script>alert('Sip! Barang keluar berhasil dicatat, stok sudah berkurang dan riwayat tersimpan.'); window.location='riwayat.php';</script>";
    } else {
        echo "<script>alert('Gagal mencatat barang keluar!');</script>";
    }
}

// Tangkap id barang dari hasil scan QR (dikirim lewat link ?id=xx dari scan.php)
$id_terpilih = isset($_GET['id']) ? $_GET['id'] : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Barang Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white fw-bold">
                    Catat Barang Keluar (Kurangi Stok)
                </div>
                <div class="card-body">

                    <?php if ($id_terpilih): ?>
                    <div class="alert alert-info py-2 small">
                        <i class="bi bi-qr-code-scan"></i> Barang otomatis terpilih dari hasil scan QR.
                    </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Pilih Barang yang Keluar</label>
                            <!-- Dropdown pilihan barang otomatis dari database -->
                            <select name="id_barang" class="form-select" required>
                                <option value="" disabled <?php if (!$id_terpilih) echo 'selected'; ?>>-- Pilih Barang --</option>
                                <?php
                                $query_dropdown = "SELECT * FROM barang ORDER BY nama_barang ASC";
                                $hasil_dropdown = mysqli_query($koneksi, $query_dropdown);
                                
                                while ($pilih = mysqli_fetch_assoc($hasil_dropdown)) {
                                    $terpilih = ($id_terpilih && $id_terpilih == $pilih['id_barang']) ? 'selected' : '';
                                    echo "<option value='".$pilih['id_barang']."' $terpilih>".htmlspecialchars($pilih['nama_barang'])." (Sisa Stok: ".$pilih['stok_sekarang'].")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jumlah Keluar</label>
                            <input type="number" name="jumlah_keluar" class="form-control" required placeholder="Contoh: 10" min="1">
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="inti.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" name="catat" class="btn btn-danger">Catat & Kurangi Stok</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>