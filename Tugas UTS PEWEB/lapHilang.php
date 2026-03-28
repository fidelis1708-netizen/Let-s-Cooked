<?php
session_start();
// 1. Koneksi Database
$conn = mysqli_connect("localhost", "root", "", "lostnf");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Logika Hapus Permanen (Jika diperlukan)
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
    
    <link rel="stylesheet" href="desain/sidebar.css">
    <style>
        body { background-color: #f4f7f6; }
        .main-content { margin-left: 250px; padding: 40px; } /* Sesuaikan margin dengan lebar sidebar kamu */
        .card-arsip { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; }
        .card-arsip:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .badge-selesai { background-color: #2ecc71; color: white; padding: 5px 15px; border-radius: 50px; font-size: 0.8rem; }
        .detail-label { font-size: 0.75rem; color: #95a5a6; text-transform: uppercase; font-weight: bold; margin-bottom: 2px; }
        .detail-value { font-size: 0.95rem; color: #2c3e50; margin-bottom: 15px; }
        .divider { border-bottom: 1px dashed #eee; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-4 position-fixed">
                <div class="text-center mb-5 text-white">
                    <i class="fa-solid fa-train-subway fa-3x mb-2"></i>
                    <h5 class="fw-bold">CommuterLink</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white-50" href="admin.php"><i class="fa-solid fa-house me-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link active text-white" href="riwayat_selesai.php"><i class="fa-solid fa-clipboard-check me-2"></i> Laporan Selesai</a>
                    </li>
                    <hr class="text-white-50">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php"><i class="fa-solid fa-power-off me-2"></i> Keluar</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-10 offset-md-2 p-5">
                <div class="mb-5">
                    <h3 class="fw-bold text-dark">Arsip Laporan Selesai</h3>
                    <p class="text-muted">Daftar lengkap laporan yang sudah berhasil diselesaikan/dikembalikan ke pemilik.</p>
                </div>

                <div class="row">
                    <?php
                    // Query mengambil data lengkap yang statusnya 'selesai'
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
                            <div class="card card-arsip p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold text-primary mb-1"><?= htmlspecialchars($row['nama_barang']) ?></h5>
                                        <span class="badge-selesai"><i class="fa-solid fa-check-double me-1"></i> Telah Kembali</span>
                                    </div>
                                    <a href="riwayat_selesai.php?hapus_arsip=<?= $row['id_laporan'] ?>" 
                                       class="text-muted" onclick="return confirm('Hapus dari arsip?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="detail-label">Nama Pelapor</div>
                                        <div class="detail-value fw-bold"><?= htmlspecialchars($row['nama']) ?></div>
                                    </div>
                                    <div class="col-6">
                                        <div class="detail-label">Tanggal Hilang</div>
                                        <div class="detail-value"><?= date('d F Y', strtotime($row['tanggal_hilang'])) ?></div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                <div class="detail-label">Lokasi Kejadian</div>
                                <div class="detail-value"><i class="fa-solid fa-location-dot text-danger me-1"></i> <?= htmlspecialchars($row['lokasi_hilang']) ?></div>

                                <div class="detail-label">Deskripsi Barang</div>
                                <div class="detail-value text-muted italic">"<?= htmlspecialchars($row['deskripsi_barang'] ?? 'Tidak ada deskripsi tambahan') ?>"</div>
                                
                                <div class="divider"></div>
                                
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

</body>
</html>