<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_pelapor = $_SESSION['id_user']; 

// Ambil semua laporan yang belum selesai agar user bisa pantau
$query = "SELECT * FROM laporan_kehilangan WHERE id_pelapor = '$id_pelapor' AND status_laporan != 'selesai' ORDER BY id_laporan DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantau Status - Lost & Found KRL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="desain/pantau.css"> 
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pantau Status Laporan</h3>
            <p class="text-muted">Lacak perkembangan laporan barang hilang kamu.</p>
        </div>
        <a href="home.php" class="btn btn-outline-secondary btn-sm">Kembali ke Dashboard</a>
    </div>

    <div class="row">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): 
                $status = $row['status_laporan'];
                
                // Logika penentuan warna dan teks status agar sinkron dengan admin
                if ($status == 'cocok') {
                    $badge_bg = "bg-warning text-dark"; 
                    $status_text = "🔍 Menunggu Verifikasi";
                } elseif ($status == 'mencari') {
                    $badge_bg = "bg-primary";
                    $status_text = "🕒 Sedang Dicari Petugas";
                } else {
                    $badge_bg = "bg-secondary";
                    $status_text = "Diproses";
                }

                $path_foto = "uploads/" . $row['foto_barang'];
                $pakai_foto = (!empty($row['foto_barang']) && file_exists($path_foto));
            ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 60px; height: 60px; background: #eee; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                        <?php if ($pakai_foto): ?>
                                            <img src="<?= $path_foto; ?>" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <span style="font-size: 24px;">📦</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold"><?= htmlspecialchars($row['nama_barang']); ?></h5>
                                        <small class="text-muted">ID: #LPN-<?= $row['id_laporan']; ?></small>
                                    </div>
                                </div>
                                <span class="badge <?= $badge_bg; ?> rounded-pill"><?= $status_text; ?></span>
                            </div>
                            
                            <p class="text-secondary mb-4" style="font-size: 0.9rem;">
                                <?= htmlspecialchars($row['deskripsi_ciri_khusus']); ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($row['tanggal_hilang'])); ?>
                                </small>
                                <a href="detail_laporan.php?id=<?= $row['id_laporan']; ?>" class="btn btn-sm btn-primary px-3 rounded-pill">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="mb-3" style="font-size: 60px; opacity: 0.3;">📄</div>
                <h4 class="fw-bold text-muted">Belum Ada Laporan Aktif</h4>
                <p class="text-muted">Semua laporanmu sudah selesai atau kamu belum membuat laporan.</p>
                <a href="laporan.php" class="btn btn-primary rounded-pill px-4 mt-2">Buat Laporan Sekarang</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>