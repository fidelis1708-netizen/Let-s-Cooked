<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// --- LOGIKA 1: SIMPAN BARANG TEMUAN BARU ---
if (isset($_POST['submit_simpan'])) {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $lokasi = $_POST['lokasi'];
    $peron = $_POST['lokasi_peron'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $id_petugas = $_SESSION['id_user'];

    $foto = $_FILES['foto_barang']['name'];
    $tmp = $_FILES['foto_barang']['tmp_name'];
    $path = "uploads/" . $foto;

    if (move_uploaded_file($tmp, $path)) {
        $query = "INSERT INTO barang_temuan (nama_barang, lokasi_temuan, lokasi_peron, tanggal_temuan, deskripsi, foto_barang, id_petugas, status) 
                  VALUES ('$nama_barang', '$lokasi', '$peron', '$tanggal', '$deskripsi', '$foto', '$id_petugas', 'tersedia')";
        mysqli_query($conn, $query);
        echo "<script>alert('Data Berhasil Disimpan!'); window.location='admin.php';</script>";
    }
}

// --- LOGIKA 2: VERIFIKASI DENGAN PESAN (MODAL) ---
if (isset($_POST['submit_verif'])) {
    $id_laporan = $_POST['id_laporan'];
    $id_barang = $_POST['id_barang'];
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan_admin']);
    $id_petugas = $_SESSION['id_user'];

    $query = "UPDATE laporan_kehilangan SET 
              id_barang_cocok = '$id_barang', 
              status_laporan = 'cocok',
              pesan_admin = '$pesan' 
              WHERE id_laporan = '$id_laporan'";

    if (mysqli_query($conn, $query)) {
        mysqli_query($conn, "INSERT INTO klaim_pencocokan (id_laporan, id_barang, id_petugas, tanggal_klaim, pesan_admin) 
                             VALUES ('$id_laporan', '$id_barang', '$id_petugas', NOW(), '$pesan')");
        mysqli_query($conn, "UPDATE barang_temuan SET status = 'diproses' WHERE id_barang = '$id_barang'");
        echo "<script>alert('Verifikasi Terkirim!'); window.location='admin.php';</script>";
    }
}

// --- LOGIKA 3: UPDATE STATUS CEPAT ---
if (isset($_POST['update_status'])) {
    $id_lpn = $_POST['id_laporan'];
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE laporan_kehilangan SET status_laporan = '$status_baru' WHERE id_laporan = '$id_lpn'");
    header("Location: admin.php");
}

// --- LOGIKA 4: HAPUS LAPORAN ---
if (isset($_GET['hapus_laporan'])) {
    $id_hapus = $_GET['hapus_laporan'];
    mysqli_query($conn, "DELETE FROM laporan_kehilangan WHERE id_laporan = '$id_hapus'");
    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Lost & Found KRL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="desain/sidebar_admin.css">
    <link rel="stylesheet" href="desain/admin.css">
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
                    <a class="nav-link" href="barang_temuan.php">
                        <i class="fa-solid fa-box-open me-2"></i> Barang Temuan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="laporan_hilang.php">
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

        <div class="main-content-area">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold mb-1">Manajemen Lost & Found</h2>
                        <p class="text-muted">Pantau laporan kehilangan dan input barang temuan baru secara real-time.</p>
                    </div>
                    <div class="text-end bg-white p-3 rounded-4 shadow-sm border">
                        <span class="fw-bold d-block text-primary"><?= htmlspecialchars($_SESSION['username']); ?></span>
                        <small class="text-muted"><i class="fa-solid fa-circle text-success me-1" style="font-size: 10px;"></i> Petugas Aktif</small>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-4 col-lg-5">
                        <div class="card p-4">
                            <div class="card-header bg-transparent border-0 p-0 mb-4">
                                <h5 class="fw-bold m-0"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Tambah Data Temuan</h5>
                            </div>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control py-2" placeholder="Misal: Dompet Kulit Cokelat" required>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label">Lokasi Stasiun</label>
                                        <select name="lokasi" class="form-select py-2" required>
                                            <option value="" selected disabled>Pilih Stasiun</option>
                                            <option value="Stasiun Serpong">Serpong</option>
                                            <option value="Stasiun Tangerang">Tangerang</option>
                                            <option value="Stasiun Bogor">Bogor</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="form-label">No. Peron</label>
                                        <input type="text" name="lokasi_peron" class="form-control py-2" placeholder="Peron 2" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Ditemukan</label>
                                    <input type="date" name="tanggal" class="form-control py-2" value="<?= date('Y-m-d'); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi & Ciri Khas</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Contoh: Ada gantungan kunci boneka..."></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Unggah Foto</label>
                                    <input type="file" name="foto_barang" class="form-control py-2" accept="image/*" required>
                                </div>
                                <button type="submit" name="submit_simpan" class="btn btn-primary w-100 py-2 fw-bold">
                                    <i class="fa-solid fa-save me-2"></i> Simpan Data
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-xl-8 col-lg-7">
                        <div class="card p-4">
                            <div class="card-header bg-transparent border-0 p-0 mb-4 d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold m-0"><i class="fa-solid fa-clipboard-list text-primary me-2"></i>Antrean Laporan Penumpang</h5>
                                <span class="badge bg-light text-primary border border-primary px-3 py-2">Terbaru</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Pelapor</th>
                                            <th>Detail Barang</th>
                                            <th>Waktu & Lokasi</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql_laporan = "SELECT l.*, u.nama FROM laporan_kehilangan l JOIN users u ON l.id_pelapor = u.id_user WHERE l.status_laporan != 'selesai' ORDER BY l.id_laporan DESC";
                                        $result_laporan = mysqli_query($conn, $sql_laporan);
                                        while ($row = mysqli_fetch_assoc($result_laporan)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($row['nama']); ?></div>
                                                <small class="text-muted">ID: #<?= $row['id_pelapor']; ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                            <td>
                                                <div class="small fw-bold"><?= date('d M Y', strtotime($row['tanggal_hilang'])); ?></div>
                                                <div class="small text-muted">
                                                <?= htmlspecialchars($row['lokasi_hilang']) . " (" . htmlspecialchars($row['peron']) . ")"; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <form action="" method="POST">
                                                    <input type="hidden" name="id_laporan" value="<?= $row['id_laporan']; ?>">
                                                    <select name="status_baru" class="form-select form-select-sm rounded-pill px-4" onchange="this.form.submit()">
                                                        <option value="mencari" <?= ($row['status_laporan'] == 'mencari') ? 'selected' : ''; ?>>Cari</option>
                                                        <option value="cocok" <?= ($row['status_laporan'] == 'cocok') ? 'selected' : ''; ?>>Verify</option>
                                                    </select>
                                                    <input type="hidden" name="update_status" value="1">
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="admin.php?hapus_laporan=<?= $row['id_laporan']; ?>" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Hapus laporan ini?')">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                    <?php if ($row['status_laporan'] !== 'mencari'): ?>
                                                        <button type="button" class="btn btn-sm btn-warning px-3" data-bs-toggle="modal" data-bs-target="#modalVerif<?= $row['id_laporan'] ?>">
                                                            <i class="fa-solid fa-paper-plane me-1"></i> Verif
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="modalVerif<?= $row['id_laporan'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content text-start">
                                                    <form action="" method="POST">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title fw-bold">Konfirmasi Penemuan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id_laporan" value="<?= $row['id_laporan'] ?>">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Pilih Barang yang Cocok:</label>
                                                                <select name="id_barang" class="form-select" required>
                                                                    <option value="" disabled selected>-- Pilih Barang Temuan --</option>
                                                                    <?php
                                                                    $res_b = mysqli_query($conn, "SELECT * FROM barang_temuan WHERE status = 'tersedia'");
                                                                    while($b = mysqli_fetch_assoc($res_b)) {
                                                                        echo "<option value='".$b['id_barang']."'>".$b['nama_barang']." (".$b['lokasi_temuan'].")</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Pesan untuk Pelapor:</label>
                                                                <textarea name="pesan_admin" class="form-control" rows="3" placeholder="Contoh: Silakan ambil barang di loket Stasiun Bogor..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="submit_verif" class="btn btn-primary w-100 fw-bold">Kirim Notifikasi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>