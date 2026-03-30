<?php
// 1. KONEKSI & SESSION
session_start();
$conn = mysqli_connect("localhost", "root", "", "lostnf");

// Proteksi halaman admin
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// 2. LOGIKA HAPUS BARANG (Sekaligus Hapus File Foto & Data Terelasi)
if (isset($_GET['hapus_barang'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus_barang']);
    
    // Ambil nama file foto dulu
    $sql_foto = "SELECT foto_barang FROM barang_temuan WHERE id_barang = '$id_hapus'";
    $res_foto = mysqli_query($conn, $sql_foto);
    $data_foto = mysqli_fetch_assoc($res_foto);
    
    if ($data_foto && !empty($data_foto['foto_barang'])) {
        $path_file = "uploads/" . $data_foto['foto_barang'];
        if (file_exists($path_file)) {
            unlink($path_file); // Hapus foto dari folder uploads
        }
    }

    // PENTING: Hapus dulu data di tabel klaim_pencocokan agar tidak error Foreign Key
    mysqli_query($conn, "DELETE FROM klaim_pencocokan WHERE id_barang = '$id_hapus'");

    // Baru hapus data dari database utama
    $query_hapus = "DELETE FROM barang_temuan WHERE id_barang = '$id_hapus'";
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>alert('Barang Berhasil Dihapus!'); window.location='barang_temuan.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Barang Temuan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="desain/sidebar_admin.css">
    <link rel="stylesheet" href="desain/admin.css">
    <style>
        body { background-color: #f4f7f6; }
        .nav-link { color: rgba(255,255,255,0.7); border-radius: 8px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #34495e; color: white; }
        .card-barang { border: none; border-radius: 12px; transition: 0.3s; }
        .card-barang:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .img-box { height: 200px; overflow: hidden; border-radius: 12px 12px 0 0; background: #ddd; }
        .img-box img { width: 100%; height: 100%; object-fit: cover; }
        .badge-status { position: absolute; top: 10px; right: 10px; font-size: 0.7rem; }
        .main-content { margin-left: 16.66667%; padding: 40px; }
    </style>
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
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'laporan_selesai.php') ? 'active' : '' ?>" href="laporan_selesai.php">
                        <i class="fa-solid fa-clipboard-check me-2"></i> Laporan Selesai
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

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0">📦 Katalog Barang Temuan</h3>
                    <p class="text-muted">Manajemen inventaris barang temuan di stasiun.</p>
                </div>
                <a href="admin.php" class="btn btn-primary px-4 rounded-pill shadow-sm"><i class="fa-solid fa-plus me-2"></i>Input Baru</a>
            </div>

            <div class="row">
                <?php
                // Query hanya mengambil barang yang BELUM selesai
                $query = "SELECT * FROM barang_temuan 
                          WHERE status NOT IN ('selesai', 'Selesai') 
                          ORDER BY id_barang DESC";
                $result = mysqli_query($conn, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $bg = 'bg-secondary';
                        $status_label = strtolower($row['status']);
                        if($status_label == 'tersedia') $bg = 'bg-info';
                        if($status_label == 'diproses') $bg = 'bg-warning text-dark';
                        if($status_label == 'dikembalikan' || $status_label == 'dikembalikan') $bg = 'bg-success text-dark';
                ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card card-barang shadow-sm h-100">
                            <span class="badge <?= $bg ?> badge-status text-uppercase"><?= $row['status'] ?></span>
                            
                            <div class="img-box">
                                <?php if($row['foto_barang']): ?>
                                    <img src="uploads/<?= $row['foto_barang'] ?>" alt="Foto">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <h6 class="fw-bold text-truncate mb-1"><?= htmlspecialchars($row['nama_barang']) ?></h6>
                                <p class="small text-muted mb-2">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= htmlspecialchars($row['lokasi_temuan']) ?>
                                </p>
                                <p class="card-text small text-secondary text-truncate-2" style="height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($row['deskripsi']) ?>
                                </p>
                            </div>

                            <div class="card-footer bg-white border-0 d-flex gap-2 pb-3">
                                <button class="btn btn-sm btn-outline-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id_barang'] ?>">Detail</button>
                                <a href="barang_temuan.php?hapus_barang=<?= $row['id_barang'] ?>" class="btn btn-sm btn-light text-danger" onclick="return confirm('Hapus barang ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detailModal<?= $row['id_barang'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Rincian Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="uploads/<?= $row['foto_barang'] ?>" class="w-100 rounded mb-3 shadow-sm">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between"><strong>Nama Barang:</strong> <span><?= htmlspecialchars($row['nama_barang']) ?></span></li>
                                        <li class="list-group-item d-flex justify-content-between"><strong>Stasiun:</strong> <span><?= htmlspecialchars($row['lokasi_temuan']) ?></span></li>
                                        <li class="list-group-item d-flex justify-content-between"><strong>Titik Peron:</strong> <span><?= htmlspecialchars($row['lokasi_peron']) ?></span></li>
                                        <li class="list-group-item d-flex justify-content-between"><strong>Tanggal:</strong> <span><?= date('d M Y', strtotime($row['tanggal_temuan'])) ?></span></li>
                                        <li class="list-group-item"><strong>Deskripsi:</strong><br><span class="text-muted"><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php 
                    } 
                } else {
                    echo "<div class='col-12 text-center py-5'><h5 class='text-muted'>Belum ada data barang temuan yang aktif.</h5></div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>