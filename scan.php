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
    <title>Scan QR Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        #reader { border-radius: 10px; overflow: hidden; }
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
                        <i class="bi bi-plus-circle me-2"></i> Tambah Stok
                    </a>
                    <a href="keluar.php" class="list-group-item list-group-item-action py-3 text-danger fw-semibold">
                        <i class="bi bi-box-arrow-right me-2"></i> Barang Keluar
                    </a>
                    <a href="riwayat.php" class="list-group-item list-group-item-action py-3">
                        <i class="bi bi-clock-history me-2"></i> Riwayat Keluar
                    </a>
                    <a href="scan.php" class="list-group-item list-group-item-action active py-3">
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
                <h4 class="text-dark mb-0">Scan QR Barang</h4>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">

                    <p class="text-muted">Arahkan QR code barang ke kamera laptop kamu.</p>

                    <div id="reader" class="mx-auto" style="width: 100%; max-width: 450px;"></div>

                    <!-- Kartu hasil scan, muncul setelah QR terdeteksi -->
                    <div id="hasil-scan" class="mt-4" style="display:none;">
                        <div class="card border-success shadow-sm">
                            <div class="card-header bg-success text-white fw-bold">
                                <i class="bi bi-check-circle me-1"></i> Barang Ditemukan
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-3">
                                    <tr>
                                        <td width="150" class="text-muted">Nama Barang</td>
                                        <td class="fw-bold" id="nama-barang">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Kategori</td>
                                        <td id="kategori-barang">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Sisa Stok</td>
                                        <td class="fw-bold fs-5 text-secondary" id="stok-barang">-</td>
                                    </tr>
                                </table>

                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="#" id="btn-edit" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Edit Barang
                                    </a>
                                    <a href="#" id="btn-keluar" class="btn btn-danger">
                                        <i class="bi bi-box-arrow-right"></i> Catat Barang Keluar
                                    </a>
                                    <button type="button" id="btn-scan-lagi" class="btn btn-secondary">
                                        <i class="bi bi-arrow-repeat"></i> Scan Barang Lain
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pesan-error" class="alert alert-danger mt-4" style="display:none;"></div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let sedangProses = false;

function onScanSuccess(decodedText) {
    if (sedangProses) return; // cegah scan dobel beruntun
    sedangProses = true;

    fetch('cek_barang.php?id=' + encodeURIComponent(decodedText))
        .then(res => res.json())
        .then(data => {
            document.getElementById('pesan-error').style.display = 'none';

            if (data.status === 'ok') {
                document.getElementById('nama-barang').innerText = data.nama_barang;
                document.getElementById('kategori-barang').innerText = data.kategori;
                document.getElementById('stok-barang').innerText = data.stok_sekarang;
                document.getElementById('btn-edit').href = 'edit.php?id=' + data.id_barang;
                document.getElementById('btn-keluar').href = 'keluar.php?id=' + data.id_barang;

                document.getElementById('hasil-scan').style.display = 'block';
                
                // PERBAIKAN: Cegah error saat menggunakan fitur "Scan an Image"
                try {
                    html5QrcodeScanner.pause();
                } catch (e) {
                    console.log("Kamera tidak aktif (mode gambar), pause dilewati.");
                }
            } else {
                document.getElementById('pesan-error').innerText = data.pesan || 'Barang tidak ditemukan.';
                document.getElementById('pesan-error').style.display = 'block';
                sedangProses = false; // boleh coba scan lagi
            }
        })
        .catch((err) => {
            console.error("Error Sistem: ", err); // Membantu melihat error asli di console
            document.getElementById('pesan-error').innerText = 'Gagal menghubungi server.';
            document.getElementById('pesan-error').style.display = 'block';
            sedangProses = false;
        });
}

function onScanFailure() {
    // dibiarkan kosong, ini dipanggil terus tiap frame yang gagal detect
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    { fps: 10, qrbox: 250 },
    false
);
html5QrcodeScanner.render(onScanSuccess, onScanFailure);

document.getElementById('btn-scan-lagi').addEventListener('click', function () {
    document.getElementById('hasil-scan').style.display = 'none';
    sedangProses = false;
    
    // PERBAIKAN: Cegah error saat menekan tombol scan lagi di mode gambar
    try {
        html5QrcodeScanner.resume();
    } catch (e) {
        console.log("Kamera tidak aktif (mode gambar), resume dilewati.");
    }
});
</script>

</body>
</html>