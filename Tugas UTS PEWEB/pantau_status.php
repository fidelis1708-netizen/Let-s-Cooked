<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_pelapor = $_SESSION['id_user']; 

$query = "SELECT * FROM laporan_kehilangan WHERE id_pelapor = '$id_pelapor' AND status_laporan != 'selesai' ORDER BY id_laporan DESC";
$result = mysqli_query($conn, $query); //
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s;
            background: #ffffff;
        }
        .status-card:hover { transform: translateY(-5px); }
        .icon-box {
            width: 50px; height: 50px;
            background: #f8f9fa;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }
        .progress-label { font-size: 0.85rem; font-weight: 600; color: #6c757d; }
        .badge-proses { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    </style>
</head>
<body style="background-color: #f4f7fe;">

<div class="container py-5">
    <h3 class="fw-bold mb-1">Pantau Status Laporan</h3>
    <p class="text-muted mb-4">Laporan yang sedang dalam tahap verifikasi oleh petugas.</p>

    <div class="row">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 mb-4">
            <div class="card status-card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box me-3 text-primary">📦</div>
                        <div>
                            <h5 class="mb-0 fw-bold"><?= $row['nama_barang']; ?></h5>
                            <small class="text-muted small">ID Laporan: #LPN-<?= $row['id_laporan']; ?></small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="progress-label d-block mb-1">Status Saat Ini:</label>
                        <span class="badge badge-proses p-2 px-3 rounded-pill">
                             🕒 Sedang Dicocokkan oleh Petugas
                        </span>
                    </div>

                    <p class="text-secondary small mb-3"><?= $row['deskripsi_ciri_khusus']; ?></p>
                    
                    <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Dilaporkan pada: <?= date('d M Y', strtotime($row['tanggal_hilang'])); ?></span>
                        <a href="#" class="btn btn-sm btn-primary rounded-pill px-3">Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>