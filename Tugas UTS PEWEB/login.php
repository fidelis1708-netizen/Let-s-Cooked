<?php
session_start();
require 'koneksi.php'; // Pastikan kamu sudah membuat file koneksi ke database lostnf

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password (Gunakan password_verify jika dipassword di-hash)
        if ($password === $row['password']) {
            // Set Session
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];

            // Redirect berdasarkan Role 
            if ($row['role'] == 'petugas') {
                header("Location: dashboard_petugas.php");
            } else {
                header("Location: dashboard_pelapor.php");
            }
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found KRL - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="login-card shadow-sm overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 d-none d-md-block left-panel">
                    <div class="content">
                        <small class="fw-bold">Lost & Found KRL</small>
                        <h1 class="fw-bold mt-2">Lost & Found KRL</h1>
                        <p>Temukan barang hilangmu dengan mudah dan cepat</p>
                    </div>
                    <div class="image-container">
                        <img src="image/KERETAA.jpeg" alt="Background Illustration">
                    </div>
                </div>

                <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center">
                    <div class="form-container px-lg-4">
                        <div class="d-flex justify-content-center mb-4 border-bottom">
                            <a href="login.php" class="nav-link active-tab pb-2 px-3 fw-bold text-primary">Login</a>
                            <a href="register.php" class="nav-link text-muted pb-2 px-3">Register</a>
                        </div>

                        <h3 class="fw-bold mb-4">Login</h3>

                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger small py-2" role="alert">
                                Email atau Password salah!
                            </div>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email</label>
                                <div class="input-group border rounded">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-0 shadow-none" placeholder="Email" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small">Password</label>
                                <div class="input-group border rounded">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-0 shadow-none" placeholder="Password" required>
                                </div>
                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100 py-2 mb-3 shadow-sm">Login</button>

                            <p class="text-center small text-muted">
                                Belum punya akun? <a href="register.php" class="text-decoration-none">Register</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="footer py-3 text-center text-muted small border-top bg-white">
                © 2024 Lost & Found KRL. All rights reserved.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>