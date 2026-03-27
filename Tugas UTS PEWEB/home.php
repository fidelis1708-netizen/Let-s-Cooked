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
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>

<div class="app-container">
    <aside class="sidebar">
        <h2 class="logo">🔍 Lost & Found KRL</h2>
        <ul>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" onclick="location.href='home.php'">Dashboard</li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'laporan.php') ? 'active' : ''; ?>" onclick="location.href='laporan.php'">Laporkan Kehilangan</li>
            <li onclick="location.href='temuan.php'">Panduan Melaporkan</li>
            <li onclick="location.href='profil.php'">Profil</li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="fixed-top-section">
            <div class="banner">
                <div class="banner-text">
                    <h1>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <p>Temukan atau laporkan barang hilang di area KRL dengan mudah.</p>
                </div>
            </div>

            <div class="cards">
                <div class="card" onclick="location.href='riwayat_selesai.php'">
                    <h3>📋 Riwayat Laporan</h3>
                    <p>Daftar laporan selesai</p>
                </div>

                <div class="card" onclick="location.href='pantau_status.php'">
                    <h3>🔎 Pantau Status Barang</h3>
                    <p>Lihat status laporanmu</p>
                </div>
            </div>  
        </div>

        <div class="scroll-area">
            <div class="notif-box">
                <h2>Notifikasi</h2>
                <?php
                include 'koneksi.php';
                $id_pelapor = $_SESSION['id_user']; 

                $sql = "SELECT * FROM laporan_kehilangan WHERE id_pelapor = '$id_pelapor' ORDER BY id_laporan DESC";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $icon = "🕒"; 
                        if ($row['status_laporan'] == 'cocok') $icon = "🔍";
                        if ($row['status_laporan'] == 'selesai') $icon = "✅";
                ?>
                        <div class="notif">
                            <span><?php echo $icon; ?></span>
                            <div>
                                <h4>Laporan <?php echo htmlspecialchars($row['nama_barang']); ?></h4>
                                <p><?php echo htmlspecialchars($row['deskripsi_ciri_khusus']); ?></p>
                            </div>
                            <small><?php echo date('d M', strtotime($row['tanggal_hilang'])); ?></small>
                        </div>
                <?php 
                    }
                } else {
                    echo "<div style='text-align:center; padding: 20px; color: gray;'>Belum ada laporan kehilangan yang dibuat.</div>";
                }
                ?>
            </div>
        </div>

        <footer class="main-footer">
            © 2024 Lost & Found KRL. All rights reserved.
        </footer>
    </main>
</div>
jawa hitam
</body>
</html>
