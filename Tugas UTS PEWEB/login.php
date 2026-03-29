<?php
session_start();
include "koneksi.php"; 

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password']) || $password == $row['password']) {
            
            $_SESSION['username'] = $row['nama']; 
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['role'] = $row['role']; 
            
            if ($row['role'] === 'petugas') {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit();
            
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found KRL - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="desain/auth.css">
</head>
<body>

<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 bg-overall">
    <div class="login-card shadow-lg overflow-hidden">
        <div class="row g-0">
            <div class="col-md-6 d-none d-md-block left-panel">
                <div class="content">
                    <small class="fw-bold text-uppercase tracking-wider">Lost & Found KRL</small>
                    <h1 class="fw-bold mt-2">Lost & Found KRL</h1>
                    <p>Temukan barang hilangmu dengan mudah dan cepat</p>
                </div>
                <div class="image-container">
                    <img src="image/KERETAA.jpeg" alt="KRL Illustration" onerror="this.src='https://via.placeholder.com/400x300?text=KRL+Illustration'">
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center justify-content-center bg-panel-right">
                <div class="inner-form-box fade-in-up">
                    <div class="d-flex justify-content-center mb-4 border-bottom">
                        <a href="login.php" class="nav-link active-tab pb-2 px-3 fw-bold">Login</a>
                        <a href="register.php" class="nav-link text-muted pb-2 px-3">Register</a>
                    </div>

                    <div class="form-content">
                        <h3 class="fw-bold mb-4 text-dark">Login</h3>

                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger small p-2"><?= $error; ?></div>
                        <?php endif; ?>
                        
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email</label>
                                <div class="input-group border rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-transparent border-0">
                                        <i class="fa-regular fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control border-0 shadow-none" placeholder="Email" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small">Password</label>
                                <div class="input-group border rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-transparent border-0">
                                        <i class="fa-solid fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control border-0 shadow-none" placeholder="Password" required>
                                </div>
                            </div>

                            <button type="submit" name="login" class="btn btn-primary w-100 py-2 mb-3 shadow-sm fw-bold">
                                Login
                            </button>

                            <p class="text-center small text-muted">
                                Belum punya akun? <a href="register.php" class="text-decoration-none fw-bold text-primary">Register</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-footer text-center py-3 border-top bg-light">
            <p class="m-0 small text-muted">© 2024 Lost & Found KRL. All rights reserved.</p>
        </div>
    </div>
</div>

</body>
</html>