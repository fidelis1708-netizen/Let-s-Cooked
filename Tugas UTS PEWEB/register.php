<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    // 1. Cek apakah password sama
    if ($password !== $konfirmasi) {
        echo "<script>alert('Konfirmasi password tidak sesuai!'); window.location='register.php';</script>";
    } else {
        // 2. Enkripsi password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 3. Set role otomatis sebagai 'pelapor' (Customer)
        $role = 'pelapor';

        // 4. Cek apakah email sudah terdaftar
        $cek_email = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
        if (mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Email sudah digunakan!'); window.location='register.php';</script>";
        } else {
            // 5. Insert ke database
            $query = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$hashed_password', '$role')";
            
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Registrasi Berhasil! Silahkan Login.'); window.location='login.php';</script>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found KRL - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="desain/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="login-card shadow-sm overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 d-none d-md-block left-panel">
                    <div class="content">
                        <small class="fw-bold text-uppercase">Lost & Found KRL</small>
                        <h1 class="fw-bold mt-2">Lost & Found KRL</h1>
                        <p>Temukan barang hilangmu dengan mudah dan cepat</p>
                    </div>
                    <div class="image-container">
                        <img src="image/KERETAA.jpeg" alt="Background Illustration">
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-center justify-content-center bg-panel-right">
                    <div class="inner-form-box shadow-sm">
                        <div class="d-flex justify-content-center mb-4 border-bottom">
                            <a href="login.php" class="nav-link pb-2 px-3">Login</a>
                            <a href="#" class="nav-link active-tab pb-2 px-3">Register</a>
                        </div>

                        <div class="form-scroll-area">
                            <h3 class="fw-bold mb-4">Register</h3>
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Nama Lengkap</label>
                                    <div class="input-group border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-transparent border-0"><i class="fa-regular fa-user text-muted"></i></span>
                                        <input type="text" name="nama" class="form-control border-0 shadow-none" placeholder="Nama Lengkap" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Email</label>
                                    <div class="input-group border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-transparent border-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control border-0 shadow-none" placeholder="Email" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small">Password</label>
                                    <div class="input-group border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                        <input type="password" name="password" class="form-control border-0 shadow-none" placeholder="Password" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted small">Konfirmasi Password</label>
                                    <div class="input-group border rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-shield-halved text-muted"></i></span>
                                        <input type="password" name="konfirmasi_password" class="form-control border-0 shadow-none" placeholder="Konfirmasi Password" required>
                                    </div>
                                </div>

                                <button type="submit" name="register" class="btn btn-primary w-100 py-2 mb-3 shadow-sm fw-bold">Register</button>

                                <p class="text-center small text-muted">
                                    Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold">Login</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="auth-footer">
                <p>© 2024 Lost & Found KRL. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>