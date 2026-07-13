<?php
session_start(); // Mulai jalankan fitur tiket masuk (Session)
include 'koneksi.php';

// Kalau udah login, langsung masuk inti.php
if (isset($_SESSION['login'])) {
    header("Location: inti.php");
    exit;
}

// Kalau tombol login diklik
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek ke database
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $cek = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($cek) > 0) {
        // Kalau ada, masuk!
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        
        echo "<script>alert('Login Berhasil! Selamat datang.'); window.location='inti.php';</script>";
    } else {
        // Kalau salah ketik
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Gudang Agen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #3f6296; }
        .login-box { margin-top: 100px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center login-box">
        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5 text-center">
                    <h4 class="fw-bold mb-4">Login GudangKU</h4>
                    
                    <?php if(isset($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                            Username atau Password salah!
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3 text-start">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-4 text-start">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100 fw-bold">MASUK</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>