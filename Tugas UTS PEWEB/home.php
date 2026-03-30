<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_pelapor = $_SESSION['id_user']; 

$sql_notif = "SELECT l.*, k.pesan_admin 
              FROM laporan_kehilangan l
              LEFT JOIN klaim_pencocokan k ON l.id_laporan = k.id_laporan
              WHERE l.id_pelapor = '$id_pelapor' 
              ORDER BY l.id_laporan DESC LIMIT 3";
$result_notif = mysqli_query($conn, $sql_notif);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Lost & Found KRL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="desain/sidebar.css">
    <link rel="stylesheet" href="desain/home.css?v=<?= time(); ?>">
</head>
<body>


<div class="app-container">
    
    <aside class="sidebar">
        <h2 class="logo">🔍 Lost & Found KRL</h2>
        <ul>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" onclick="location.href='home.php'">Dashboard</li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'laporan.php') ? 'active' : ''; ?>" onclick="location.href='laporan.php'">Laporkan Kehilangan</li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'panduan.php') ? 'active' : ''; ?>" onclick="location.href='panduan.php'">Panduan Melaporkan</li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>" onclick="location.href='profile.php'">Profil</li>
        </ul>
    </aside>
    <main class="main-content">
        <div class="fixed-top-section">
            <div class="banner">
                <div class="banner-text">
                    <h1>Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <p>Temukan atau laporkan barang hilang di area KRL Nusantara dengan mudah.</p>
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
                <h2 class="notif-title">
                    <i class="fa-solid fa-bell text-warning me-2"></i> Notifikasi Terbaru
                </h2>
                
                <?php if(mysqli_num_rows($result_notif) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result_notif)): 
                        $status = $row['status_laporan'];
                        // Logika warna badge & border
                        $borderColor = ($status == 'cocok') ? '#f59e0b' : (($status == 'selesai') ? '#198754' : '#0d6efd');
                        $badgeClass = ($status == 'cocok') ? 'bg-warning' : (($status == 'selesai') ? 'bg-success' : 'bg-primary');
                    ?>
                        <div class="notif-card" style="border-left: 6px solid <?= $borderColor ?>;">
                            <div class="notif-content" style="width: 100%;">
                                <div class="notif-header">
                                    <h5 class="barang-name">Barang: <?= htmlspecialchars($row['nama_barang']); ?></h5>
                                    <span class="badge <?= $badgeClass ?>"><?= ucfirst($status); ?></span>
                                </div>
                                
                                <?php if ($status == 'cocok'): ?>
                                    <p class="status-desc">Kabar baik! Barang Anda telah ditemukan. Silakan hubungi petugas di stasiun terkait.</p>
                                    
                                    <div class="pesan-box">
                                        <strong><i class="fa-solid fa-comment-dots me-1"></i> Pesan Petugas:</strong><br>
                                        <?= htmlspecialchars($row['pesan_admin'] ?? 'Silakan ambil barangnya di stasiun dengan membawa kartu identitas anda.'); ?>
                                    </div>

                                    <a href="proses_verifikasi.php?id=<?= $row['id_laporan']; ?>" 
                                       class="btn-konfirmasi" 
                                       onclick="return confirm('Konfirmasi bahwa Anda sudah menerima kembali barang ini?')">
                                        <i class="fa-solid fa-circle-check"></i> Saya Sudah Ambil Barangnya
                                    </a>

                                <?php elseif ($status == 'selesai'): ?>
                                    <p class="status-done">
                                        <i class="fa-solid fa-circle-check me-1"></i> Transaksi selesai. Barang telah diserahkan kembali.
                                    </p>
                                    <script>
                                        setTimeout(function(){
                                            document.getElementById('notif-<?= $row['id_laporan'] ?>').style.display = 'none';
                                        }, 1999);
                                    </script>
                                <?php else: ?>
                                    <p class="status-desc">Laporan Anda sedang dalam tahap pengecekan oleh petugas kami.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-notif">
                        <i class="fa-solid fa-inbox"></i>
                        <p>Belum ada laporan terbaru.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <footer class="main-footer">
            © 2026 Lost & Found KRL. All rights reserved.
        </footer>
    </main>
</div>

</body>
</html>