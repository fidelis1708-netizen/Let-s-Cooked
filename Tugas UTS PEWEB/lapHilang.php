<?php
session_start();
// 1. Koneksi
$conn = mysqli_connect("localhost", "root", "", "lostnf");

// 2. Logika Update Status dengan Session Timer
if (isset($_POST['update_status'])) {
    $id_laporan = $_POST['id_laporan'];
    $status_baru = $_POST['status_baru'];

    $query_update = "UPDATE laporan_kehilangan SET status_laporan = '$status_baru' WHERE id_laporan = '$id_laporan'";
    
    if (mysqli_query($conn, $query_update)) {
        if ($status_baru == 'selesai') {
            // Catat waktu selesai di session
            $_SESSION['timer_selesai'][$id_laporan] = time();
        } else {
            // Jika dibatalkan dari 'selesai', hapus timer
            unset($_SESSION['timer_selesai'][$id_laporan]);
        }
        echo "<script>window.location='admin.php';</script>";
    }
}

// 3. Logika Hapus
if (isset($_GET['hapus_laporan'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus_laporan']);
    mysqli_query($conn, "DELETE FROM laporan_kehilangan WHERE id_laporan = '$id_hapus'");
    echo "<script>window.location='admin.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="desain/admin.css">
    <link rel="stylesheet" href="desain/sidebar.css"> <style>
        /* Desain Tambahan untuk Isi yang Lebih Bagus */
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; color: #6c757d; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .timer-info { font-size: 0.7rem; color: #e74c3c; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
        .form-select-custom { border-radius: 8px; border: 1px solid #ced4da; transition: 0.3s; }
        .form-select-custom:focus { border-color: #3498db; box-shadow: 0 0 0 0.2rem rgba(52,152,219,0.25); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 d-none d-md-block sidebar p-4 position-fixed">
                 </div>

            <div class="col-md-10 offset-md-2 p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold"><i class="fa-solid fa-clipboard-list text-primary me-2"></i> Laporan Kehilangan Aktif</h4>
                    <span class="badge bg-light text-dark border p-2"><i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y') ?></span>
                </div>

                <div class="card card-custom p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Info Pelapor</th>
                                    <th>Detail Barang</th>
                                    <th>Status Laporan</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT l.*, u.nama, u.email FROM laporan_kehilangan l 
                                        JOIN users u ON l.id_pelapor = u.id_user 
                                        ORDER BY l.id_laporan DESC";
                                $res = mysqli_query($conn, $sql);

                                while ($row = mysqli_fetch_assoc($res)) :
                                    $id = $row['id_laporan'];
                                    
                                    // Logika PHP 1 Menit
                                    if ($row['status_laporan'] == 'selesai') {
                                        if (isset($_SESSION['timer_selesai'][$id])) {
                                            if (time() - $_SESSION['timer_selesai'][$id] > 60) {
                                                continue; // Melewati baris ini (menghilangkan)
                                            }
                                        } else {
                                            continue; // Jika sudah selesai di DB tapi ga ada di session, anggap data lama & sembunyikan
                                        }
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($row['nama']) ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($row['email']) ?></div>
                                    </td>
                                    <td>
                                        <div class="text-primary fw-semibold"><?= htmlspecialchars($row['nama_barang']) ?></div>
                                        <div class="small"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= htmlspecialchars($row['lokasi_hilang']) ?></div>
                                    </td>
                                    <td>
                                        <form action="" method="POST" class="d-flex align-items-center gap-2">
                                            <input type="hidden" name="id_laporan" value="<?= $id ?>">
                                            <select name="status_baru" class="form-select form-select-sm form-select-custom w-auto" onchange="this.form.submit()">
                                                <option value="mencari" <?= ($row['status_laporan'] == 'mencari' ? 'selected' : '') ?>>Mencari</option>
                                                <option value="cocok" <?= ($row['status_laporan'] == 'cocok' ? 'selected' : '') ?>>Cocok</option>
                                                <option value="selesai" <?= ($row['status_laporan'] == 'selesai' ? 'selected' : '') ?>>Selesai</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                            
                                            <?php if($row['status_laporan'] == 'selesai'): ?>
                                                <span class="timer-info fw-bold"><i class="fa-solid fa-clock-rotate-left"></i> 1m</span>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <a href="admin.php?hapus_laporan=<?= $id ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus laporan ini?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="small text-muted mt-3 italic">* Laporan dengan status <b>Selesai</b> akan otomatis berpindah ke arsip setelah 1 menit jika halaman di-refresh.</p>
            </div>
        </div>
    </div>
</body>
</html>