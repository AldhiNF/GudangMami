<?php
$host = "localhost";
$user = "root";
$pass = ""; // Karna di Laragon password default-nya kosong
$db   = "db_gudang";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>