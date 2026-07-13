<?php 
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Barang Keluar - Gudang Agen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .list-group-item.active { background-color: #0d6efd; border-color: #0d6efd; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="inti.php">
            <i class="bi bi-shop"></i> Gudang Agen Makmur
        </a>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        
        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="bi bi-list"></i> Menu Navigasi
                </div>
                <div class="list-group list-group-flush rounded-bottom">
                    <a href="inti.php" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-clipboard-data me-2"></i> Data Stok
                    </a>
                    <a href="tambah.php" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Stok Baru
                    </a>
                    <a href="keluar.php" class="list-group-item list-group-item-action py-3 text-danger fw-semibold">
                        <i class="bi bi-box-arrow-right me-2"></i> Barang Keluar
                    </a>
                    <a href="riwayat.php" class="list-group-item list-group-item-action active py-3">
                        <i class="bi bi-clock-history me-2"></i> Riwayat Keluar
                    </a>
                    <a href="scan.php" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-qr-code-scan me-2"></i> Scan QR Barang
                    </a>
                    <a href="logout.php" class="list-group-item list-group-item-action py-3 text-danger fw-bold border-top mt-2">
                        <i class="bi bi-power me-2"></i> Keluar (Logout)
                    </a>
                </div>
            </div>
        </div>

        <!-- KONTEN UTAMA -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-dark mb-0">Riwayat Barang Keluar</h4>
                <a href="keluar.php" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Input Keluar Baru</a>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-secondary">Catatan Mutasi Barang</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Waktu Mutasi</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Jumlah Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // JOIN tabel riwayat dengan barang untuk mendapatkan nama dan kategori
                                $query_riwayat = "SELECT r.waktu_keluar, r.jumlah_keluar, b.nama_barang, b.kategori 
                                                  FROM riwayat_keluar r 
                                                  JOIN barang b ON r.id_barang = b.id_barang 
                                                  ORDER BY r.waktu_keluar DESC";
                                $hasil = mysqli_query($koneksi, $query_riwayat);
                                $no = 1;

                                if (mysqli_num_rows($hasil) == 0) {
                                    echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Belum ada riwayat barang keluar.</td></tr>";
                                }

                                while ($row = mysqli_fetch_assoc($hasil)) {
                                    // Format waktu menjadi lebih mudah dibaca (misal: 13 Jul 2026, 14:30)
                                    $waktu = date('d M Y, H:i', strtotime($row['waktu_keluar']));
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><span class="text-muted small"><i class="bi bi-calendar-event me-1"></i> <?php echo $waktu; ?></span></td>
                                    <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                    <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            - <?php echo $row['jumlah_keluar']; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>