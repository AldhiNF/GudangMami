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
    <title>Sistem Gudang Agen</title>
    <!-- CSS Bootstrap -->
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
                    <a href="inti.php" class="list-group-item list-group-item-action active py-3">
                        <i class="bi bi-clipboard-data me-2"></i> Data Stok
                    </a>
                    <a href="tambah.php" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Stok Baru
                    </a>
                    <a href="keluar.php" class="list-group-item list-group-item-action py-3 text-danger fw-semibold">
                        <i class="bi bi-box-arrow-right me-2"></i> Barang Keluar
                    </a>
                    <a href="riwayat.php" class="list-group-item list-group-item-action py-3">
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
                <h4 class="text-dark mb-0">Dashboard Data Stok</h4>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                
                <!-- HEADER CARD: Judul dan Form Filter -->
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-secondary d-none d-md-block">Daftar Ketersediaan Barang</h6>
                    
                    <div class="d-flex align-items-center">
                        <!-- Form Filter -->
                        <form action="inti.php" method="GET" class="d-flex me-3">
                            <select name="filter_kategori" class="form-select form-select-sm me-2">
                                <option value="">Semua Kategori</option>
                                <option value="Makanan" <?php if(isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == 'Makanan') echo 'selected'; ?>>Makanan</option>
                                <option value="Cemilan" <?php if(isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == 'Cemilan') echo 'selected'; ?>>Cemilan</option>
                                <option value="Minuman" <?php if(isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == 'Minuman') echo 'selected'; ?>>Minuman</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
                        </form>
                    </div>
                </div>

                <!-- BODY CARD: Tabel Data -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Nama Barang</th>
                                    <th width="15%">Kategori</th>
                                    <th width="12%" class="text-center">Sisa Stok</th>
                                    <th width="8%" class="text-center">QR</th>
                                    <th width="20%" class="text-center">Pilihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php
                                // Logika penangkapan filter kategori
                                $kondisi_filter = "";
                                if (isset($_GET['filter_kategori']) && $_GET['filter_kategori'] != "") {
                                    $kategori_dipilih = $_GET['filter_kategori'];
                                    // Tambahkan WHERE jika ada filter yang dipilih
                                    $kondisi_filter = "WHERE kategori = '$kategori_dipilih'";
                                }

                                // Query dengan urutan Kategori (A-Z) lalu Nama Barang (A-Z)
                                $query = "SELECT * FROM barang $kondisi_filter ORDER BY kategori ASC, nama_barang ASC";
                                $result = mysqli_query($koneksi, $query);
                                $no = 1;

                                // Jika data tidak ditemukan setelah difilter
                                if (mysqli_num_rows($result) == 0) {
                                    echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Tidak ada barang di kategori ini.</td></tr>";
                                }

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $kategori = strtolower($row['kategori']);
                                    $warna_badge = 'bg-secondary'; 
                                    
                                    if ($kategori == 'minuman') {
                                        $warna_badge = 'bg-info text-dark';
                                    } elseif ($kategori == 'cemilan') {
                                        $warna_badge = 'bg-warning text-dark';
                                    } elseif ($kategori == 'makanan') {
                                        $warna_badge = 'bg-success';
                                    }
                                ?>
                                
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $warna_badge; ?> rounded-pill px-3 py-2">
                                            <?php echo htmlspecialchars($row['kategori']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold fs-5 text-secondary">
                                            <?php echo $row['stok_sekarang']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-dark px-2"
                                            onclick="tampilkanQR('<?php echo $row['id_barang']; ?>', '<?php echo htmlspecialchars(addslashes($row['nama_barang'])); ?>')">
                                            <i class="bi bi-qr-code"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-sm btn-outline-primary px-2 me-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="hapus.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-sm btn-outline-danger px-2" onclick="return confirm('Hapus <?php echo htmlspecialchars(addslashes($row['nama_barang'])); ?> dari gudang?');">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
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

<!-- MODAL QR CODE -->
<div class="modal fade" id="modalQR" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold" id="judulModalQR">QR Barang</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <canvas id="canvasQR"></canvas>
                <p class="text-muted small mt-2 mb-0">Scan QR ini lewat menu "Scan QR Barang"</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-dark" id="btnPrintQR">
                    <i class="bi bi-printer"></i> Print
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDownloadQR">
                    <i class="bi bi-download"></i> Download PNG
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
let modalQR = new bootstrap.Modal(document.getElementById('modalQR'));

function tampilkanQR(idBarang, namaBarang) {
    document.getElementById('judulModalQR').innerText = namaBarang;
    modalQR.show();

    const canvas = document.getElementById('canvasQR');

    QRCode.toCanvas(canvas, idBarang, { width: 220 }, function (error) {
        if (error) {
            console.error(error);
            alert('Gagal membuat QR code.');
            return;
        }

        // Baru pasang tombol download & print SETELAH QR selesai digambar
        document.getElementById('btnDownloadQR').onclick = function () {
            canvas.toBlob(function (blob) {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = 'QR_' + namaBarang.replace(/\s+/g, '_') + '.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            }, 'image/png');
        };

        document.getElementById('btnPrintQR').onclick = function () {
            const gambarQR = canvas.toDataURL('image/png');
            const jendelaPrint = window.open('', '_blank');
            jendelaPrint.document.write(`
                <html>
                <head><title>Print QR - ${namaBarang}</title></head>
                <body style="text-align:center; font-family:sans-serif; padding-top:40px;">
                    <h4>${namaBarang}</h4>
                    <img src="${gambarQR}" style="width:220px;">
                    <script>
                        window.onload = function() { window.print(); window.close(); };
                    <\/script>
                </body>
                </html>
            `);
            jendelaPrint.document.close();
        };
    });
}
</script>

</body>
</html>