<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Lost & Found KRL</title>
    
    <link rel="stylesheet" href="desain/sidebar.css">
    <link rel="stylesheet" href="desain/profile.css">
</head>
<body>

    <div class="layout-wrapper">
        <aside class="sidebar">
            <h2 class="logo-title">🔍 Lost & Found KRL</h2>
            <nav class="sidebar-menu">
                <a href="dashboard_petugas.html">Dashboard</a>
                <a href="laporan.html">Laporkan Kehilangan</a>
                <a href="panduan.html">Panduan Melaporkan</a>
                <a href="profil.html" class="active">Profil</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <span class="user-greeting">Selamat melaporkan, <strong>jawa icikiwir</strong></span>
            </header>

            <div class="page-header">
                <h1>Profil Pengguna</h1>
                <p>Kelola informasi data diri Anda di sini.</p>
            </div>

            <div class="profile-container">
                <div class="profile-card">
                    <div class="profile-avatar">JI</div>
                    
                    <div class="profile-details">
                        <div class="info-group">
                            <label>Nama Lengkap</label>
                            <div class="info-value">jawa icikiwir</div>
                        </div>

                        <div class="info-group">
                            <label>Email</label>
                            <div class="info-value">jawa.icikiwir@email.com</div>
                        </div>

                        <div class="info-group">
                            <label>Nomor Telepon</label>
                            <div class="info-value">081234567890</div>
                        </div>

                        <button class="btn-edit">Edit Profil</button>

                        <a href="login.php" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun ini?');">Logout</a>

                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>