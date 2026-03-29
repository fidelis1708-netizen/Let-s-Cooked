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
    
    <style>
        /* Tambahan biar gambar di card rapi */
        .img-arsip {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
        }
        /* Pastikan sidebar fix dan konten tidak ketimpa */
        .sidebar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px; /* Sesuaikan dengan lebar sidebar lu */
            z-index: 1000;
        }
        .main-content {
            margin-left: 250px; /* Harus sama dengan lebar sidebar */
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="sidebar sidebar-fixed d-none d-md-block p-4">
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
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'barang_temuan.php') ? 'active' : '' ?>" href="barang_temuan.php">
                        <i class="fa-solid fa-box-open me-2"></i> Barang Temuan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_hilang.php') ? 'active' : '' ?>" href="laporan_hilang.php">
                        <i class="fa-solid fa-clipboard-check me-2"></i> Laporan Hilang
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="riwayat_selesai.php">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Selesai
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

        <div class="main-content p-5">
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
                        
                        // LOGIKA ANTI-GAGAL GAMBAR
                        $path_gambar = "";
                        if (!empty($row['gambar'])) {
                            // Cek apakah string di database sudah mengandung kata "uploads/"
                            if (strpos($row['gambar'], 'uploads/') !== false) {
                                $path_gambar = $row['gambar']; // Udah ada foldernya, biarin aja
                            } else {
                                $path_gambar = 'uploads/' . $row['gambar']; // Belum ada, tambahin folder uploads/
                            }
                        }
                ?>
                    <div class="col-md-6 mb-4">
                        <div class="card card-arsip p-4 shadow-sm border-0" style="border-radius: 15px;">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3 align