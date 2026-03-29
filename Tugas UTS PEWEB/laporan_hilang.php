<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi database sudah benar

// Proteksi Halaman: Hanya admin/petugas yang bisa masuk
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'petugas') {
    header("Location: login.php");
    exit;
}

// Logika Hapus Permanen dari Arsip
if (isset($_GET['hapus_arsip'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus_arsip']);
    mysqli_query($conn, "DELETE FROM laporan_kehilangan WHERE id_laporan = '$id_hapus'");
    echo "<script>alert('Arsip laporan dihapus permanen!'); window.location='riwayat_selesai.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan Selesai - Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="desain/sidebar_admin.css">
    <link rel="stylesheet" href="desain/laporan_hilang.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row">
        <div class="sidebar d-none d-md-block">
            <div class="text-center mb-5 text-white">
                <i class="fa-solid fa-train-subway fa-3x mb-2"></i>
                <h5 class="fw-bold">CommuterLink</h5>
                <small class="opacity-75 text-uppercase" style="font-size: 0.7rem;">Nusantara Admin</small>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'admin.php') ? 'active' : '' ?>" href="admin.php">
                        <i class="fa-solid fa-house me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'barang_temuan.php') ? 'active' : '' ?>" href="#">
                        <i class="fa-solid fa-box-open me-2"></i> Barang Temuan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_hilang.php') ? 'active' : '' ?>" href="laporan_hilang.php">
                        <i class="fa-solid fa-clipboard-check me-2"></i> Laporan Hilang
                    </a>
                </li>
                <hr class="text-white-50 my-4">
                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="logout.php">
                        <i class="fa-solid fa-power-off me-2"></i> Keluar
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-content-area p-5">
            </div>
    </div>
</div>
            <div class="col-md-10 offset-md-2 p-5">
                <div class="mb-5">
                    <h3 class="fw-bold text-dark">Arsip Laporan Selesai</h3>
                    <p class="text-muted">Daftar lengkap laporan yang sudah berhasil diselesaikan (status: selesai).</p>
                </div>

                <div class="row">
                    <?php
                    // Ambil data laporan yang sudah selesai
                    $sql_selesai = "SELECT l.*, u.nama, u.email 
                                    FROM laporan_kehilangan l 
                                    JOIN users u ON l.id_pelapor = u.id_user 
                                    WHERE l.status_laporan = 'selesai' 
                                    ORDER BY l.id_laporan DESC";
                    
                    $result = mysqli_query($conn, $sql_selesai);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <div class="col-md-6 mb-4">
                            <div class="card card-arsip p-4 shadow-sm border-0" style="border-radius: 15px;">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold text-primary mb-1"><?= htmlspecialchars($row['nama_barang']) ?></h5>
                                        <span class="badge bg-success text-white" style="border-radius:50px; padding: 5px 15px; font-size: 0.75rem;">
                                            <i class="fa-solid fa-check-double me-1"></i> Telah Kembali
                                        </span>
                                    </div>
                                    <a href="riwayat_selesai.php?hapus_arsip=<?= $row['id_laporan'] ?>" 
                                       class="text-muted" onclick="return confirm('Hapus permanen dari arsip?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-muted small fw-bold text-uppercase">Nama Pelapor</div>
                                        <div class="fw-bold"><?= htmlspecialchars($row['nama']) ?></div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small fw-bold text-uppercase">Tanggal Hilang</div>
                                        <div><?= date('d M Y', strtotime($row['tanggal_hilang'])) ?></div>
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="text-muted small fw-bold text-uppercase">Lokasi Kejadian</div>
                                <div class="mb-3"><i class="fa-solid fa-location-dot text-danger me-1"></i> <?= htmlspecialchars($row['lokasi_hilang']) ?></div>

                                <div class="text-muted small fw-bold text-uppercase">Ciri Khusus</div>
                                <div class="text-muted fst-italic">"<?= htmlspecialchars($row['deskripsi_ciri_khusus'] ?? 'Tidak ada deskripsi') ?>"</div>
                                
                                <hr class="my-3">
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted">Kontak: <?= htmlspecialchars($row['email']) ?></small>
                                    <small class="text-muted">ID: #L-<?= $row['id_laporan'] ?></small>
                                </div>
                            </div>
                        </div>
                    <?php 
                        } 
                    } else {
                        echo '<div class="col-12 text-center py-5">
                                <i class="fa-solid fa-folder-open fa-3x text-light mb-3"></i>
                                <p class="text-muted">Belum ada data laporan yang selesai.</p>
                              </div>';
                    }
                    ?>
                </div>
            </div>
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>