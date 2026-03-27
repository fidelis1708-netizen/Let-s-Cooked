<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Lost & Found KRL</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>

<div class="app-container">
    <aside class="sidebar">
        <h2 class="logo">🔍 Lost & Found KRL</h2>
        <ul>
            <li class="active">Dashboard</li>
            <li>Laporkan Kehilangan</li>
            <li>Barang Temuan</li>
            <li>Profil</li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="fixed-top-section">
            <div class="banner">
                <div class="banner-text">
                    <h1>Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
                    <p>Temukan atau laporkan barang hilang di area KRL dengan mudah.</p>
                </div>
            </div>

            <div class="cards">
                <div class="card">
                    <h3>📋 Laporan Kehilangan</h3>
                    <p>Laporkan barang hilang</p>
                </div>
                <div class="card">
                    <h3>🔎 Pantau Status Barang</h3>
                    <p>Lihat status laporanmu</p>
                </div>
            </div>
        </div>

        <div class="scroll-area">
            <div class="notif-box">
                <h2>Notifikasi</h2>
                
                <div class="notif">
                    <span>✅</span>
                    <div>
                        <h4>Laporan kamu sedang diproses</h4>
                        <p>Pengajuan laporan Dompet Coklat sedang diproses</p>
                    </div>
                    <small>10 menit lalu</small>
                </div>

                <div class="notif">
                    <span>🔍</span>
                    <div>
                        <h4>Barang mirip ditemukan</h4>
                        <p>Ditemukan di Stasiun Sudirman</p>
                    </div>
                    <small>1 jam lalu</small>
                </div>

                <div class="notif">
                    <span>⚠️</span>
                    <div>
                        <h4>Lengkapi data laporan</h4>
                        <p>Handphone Biru belum lengkap</p>
                    </div>
                    <small>2 hari lalu</small>
                </div>

                <div class="notif">
                    <span>📄</span>
                    <div>
                        <h4>Laporan kehilangan dibuat</h4>
                        <p>Kamu melaporkan Dompet Coklat</p>
                    </div>
                    <small>3 hari lalu</small>
                </div>

                <?php for($i=0; $i<5; $i++) { ?>
                <div class="notif">
                    <span>🕒</span>
                    <div>
                        <h4>Riwayat Laporan Lama</h4>
                        <p>Catatan aktivitas sebelumnya...</p>
                    </div>
                    <small>Selesai</small>
                </div>
                <?php } ?>
            </div>
        </div>

        <footer class="main-footer">
            © 2024 Lost & Found KRL. All rights reserved.
        </footer>
    </main>
</div>

</body>
</html>