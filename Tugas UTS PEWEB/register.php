<?php
require 'koneksi.php';

if (isset($_POST["register"])) {
    $nama = mysqli_real_escape_string($conn, $_POST["nama"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"]; 
    $role = $_POST["role"];

    // Cek apakah email sudah ada di database
    $result = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
    
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {
        // Insert ke tabel users sesuai struktur database
        $query = "INSERT INTO users (nama, email, password, role) 
                  VALUES ('$nama', '$email', '$password', '$role')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>
                    alert('Registrasi Berhasil!');
                    document.location.href = 'login.php';
                  </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Lost & Found KRL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="register-card shadow-sm overflow-hidden">
            <div class="row g-0">
                <div class="col-md-6 d-none d-md-block left-panel">
                    <div class="content">
                        <small class="fw-bold text-uppercase">CommuterLink Nusantara</small>
                        <h1 class="fw-bold mt-2">Daftar Akun</h1>
                        <p>Laporkan barang hilang atau kelola penemuan barang dengan sistem digital.</p>
                    </div>
                    <div class="image-container">
                        <img src="image/KERETAA.jpeg" alt="KRL Illustration">
                    </div>
                </div>

                <div class="col-md-6 bg-white p-5">
                    <div class="form-container">
                        <div class="d-flex justify-content-center mb-4 border-bottom">
                            <a href="login.php" class="nav-link text-muted pb-2 px-3">Login</a>
                            <a href="register.php" class="nav-link active-tab pb-2 px-3 fw-bold">Register</a>
                        </div>

                        <h3 class="fw-bold mb-4">Register</h3>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Nama Lengkap</label>
                                <div class="input-group border rounded">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-user text-muted"></i></span>
                                    <input type="text" name="nama" class="form-control border-0 shadow-none" placeholder="Masukkan Nama" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Email</label>
                                <div class="input-group border rounded">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-0 shadow-none" placeholder="Email@domain.com" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Password</label>
                                <div class="input-group border rounded">
                                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-0 shadow-none" placeholder="Password" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small">Daftar Sebagai (Role)</label>
                                <select name="role" class="form-select border shadow-none" required>
                                    <option value="" disabled selected>Pilih Role...</option>
                                    <option value="pelapor">Pelapor (Penumpang)</option>
                                    <option value="petugas">Petugas KRL</option>
                                </select>
                            </div>

                            <button type="submit" name="register" class="btn btn-primary w-100 py-2 mb-3 shadow-sm">Daftar</button>

                            <p class="text-center small text-muted">
                                Sudah punya akun? <a href="login.php" class="text-decoration-none">Login sekarang</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>