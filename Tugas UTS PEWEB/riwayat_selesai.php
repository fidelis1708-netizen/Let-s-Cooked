<?php
session_start(); // WAJIB ADA DI BARIS 1
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_pelapor = $_SESSION['id_user']; 

$query = "SELECT * FROM laporan_kehilangan WHERE id_pelapor = '$id_pelapor' AND status_laporan = 'selesai' ORDER BY id_laporan DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid #eef2f7;
        }
        .thead-custom { background-color: #4e73df; color: white; }
        .btn-invoice {
            background-color: #f0f4ff;
            color: #4e73df;
            border: none;
            font-weight: 600;
        }
        .btn-invoice:hover { background-color: #4e73df; color: white; }
    </style>
</head>
<body style="background-color: #f4f7fe;">

<div class="container py-5">
    <h3 class="fw-bold mb-1 text-success">Riwayat Selesai</h3>
    <p class="text-muted mb-4">Daftar laporan yang barangnya telah berhasil ditemukan dan diambil.</p>

    <div class="table-container shadow-sm">
        <table class="table table-hover align-middle mb-0">
            <thead class="thead-custom">
                <tr>
                    <th class="ps-4">Barang</th>
                    <th>Tanggal Selesai</th>
                    <th>Lokasi Pengambilan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold"><?= $row['nama_barang']; ?></div>
                            <small class="text-muted">Kategori: Personal</small>
                        </td>
                        <td><?= date('d M Y', strtotime($row['tanggal_hilang'])); ?></td>
                        <td>Stasiun Manggarai (Lost & Found)</td>
                        <td><span class="badge bg-success-soft text-success border border-success px-3">SELESAI</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-invoice rounded-pill px-3">Lihat Bukti Terpilih</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted small">Belum ada riwayat laporan yang selesai.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>