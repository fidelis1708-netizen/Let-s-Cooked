<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Melaporkan - Lost & Found KRL</title>
    
    <link rel="stylesheet" href="desain/sidebar.css">
    <link rel="stylesheet" href="desain/panduan.css">
</head>
<body>

    <div class="layout-wrapper">
        <aside class="sidebar">
        <h2 class="logo">🔍 Lost & Found KRL</h2>
            <ul>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" onclick="location.href='home.php'">Dashboard</li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'laporan.php') ? 'active' : ''; ?>" onclick="location.href='laporan.php'">Laporkan Kehilangan</li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'panduan.php') ? 'active' : ''; ?>" onclick="location.href='panduan.php'">Panduan Melaporkan</li>
            <li onclick="location.href='profil.php'">Profil</li>
        </ul>
    </aside>

        <main class="main-content">
            <header class="top-bar">
                <span class="user-greeting">Selamat melaporkan, <strong>jawa icikiwir</strong></span>
            </header>

            <div class="page-header">
                <h1>Panduan Melaporkan Kehilangan</h1>
                <p>Isi formulir berikut untuk melaporkan barang yang hilang di area KRL.</p>
            </div>

            <div class="panduan-grid">
                <a href="laporan.php" class="panduan-card">
                    <div class="step-number">1</div>
                    <h3>Isi Formulir</h3>
                    <p>Buka menu Laporkan Kehilangan dan isi semua data barang yang hilang dengan detail (Jenis, Lokasi, dan Tanggal).</p>
                </a>

                <a href="laporan.php" class="panduan-card">
                    <div class="step-number">2</div>
                    <h3>Unggah Foto</h3>
                    <p>Sertakan foto barang jika ada. Ini akan sangat membantu petugas untuk memverifikasi barang yang ditemukan.</p>
                </a>


                <a href="pantau_status.php" class="panduan-card">
                    <div class="step-number">3</div>
                    <h3>Pantau Status</h3>
                    <p>Cek menu Dashboard secara berkala untuk melihat apakah barang Anda sudah ditemukan oleh petugas atau pengguna lain.</p>
                </a>


                

                <div class="panduan-card">
                    <div class="step-number">4</div>
                    <h3>Ambil Barang</h3>
                    <p>Jika status sudah "Ditemukan", hubungi petugas di stasiun terdekat dengan membawa bukti identitas diri yang sah.</p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>