<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_pelapor = $_SESSION['id_user']; 

$query = "SELECT * FROM laporan_kehilangan WHERE id_pelapor = '$id_pelapor' AND status_laporan != 'selesai' ORDER BY id_laporan DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pantau Status - Lost & Found KRL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="desain/pantau.css"> 
</head>
<body class="status-container">

<div class="container py-5">
    <h3 class="fw-bold mb-1">Pantau Status Laporan</h3>
    <p class="text-muted mb-4">Lacak perkembangan laporan barang hilang kamu secara real-time.</p>

    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): 
                $status = $row['status_laporan'];
                if ($status == 'cocok') {
                    $badge_class = "badge-verifikasi";
                    $status_text = "🔍 Menunggu Verifikasi";
                    $default_icon = "📦"; 
                } else {
                    $badge_class = "badge-mencari";
                    $status_text = "🕒 Sedang Dicari Petugas";
                    $default_icon = "📦"; 
                }

                $path_foto = "uploads/" . $row['foto_barang'];
                $pakai_foto = (!empty($row['foto_barang']) && file_exists($path_foto));
            ?>
                <div class="col-md-6 mb-4">
                    <div class="card status-card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box me-3">
                                    <?php if ($pakai_foto): ?>
                                        <img src="<?= $path_foto; ?>" alt="Foto Barang" class="img-preview">
                                    <?php else: ?>
                                        <?= $default_icon; ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold"><?= htmlspecialchars($row['nama_barang']); ?></h5>
                                    <span class="report-id">ID: #LPN-<?= $row['id_laporan']; ?></span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="progress-label">Status Laporan:</label>
                                <span class="badge-status <?= $badge_class; ?>">
                                    <?= $status_text; ?>
                                </span>
                            </div>

                            <p class="text-secondary small mb-3"><?= htmlspecialchars($row['deskripsi_ciri_khusus']); ?></p>
                            
                            <div class="card-footer-custom">
                                <span class="text-muted small">Tgl Lapor: <?= date('d M Y', strtotime($row['tanggal_hilang'])); ?></span>
                                <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>" class="btn btn-sm btn-primary rounded-pill px-3">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3" style="font-size: 50px;">📄</div>
                <h4 class="fw-bold">Belum Ada Laporan Aktif</h4>
                <a href="laporan.php" class="btn btn-primary rounded-pill px-4 mt-2">Buat Laporan</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>