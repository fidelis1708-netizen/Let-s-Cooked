<?php
// Koneksi ke database
session_start();
$conn = mysqli_connect("localhost", "root", "", "lostnf");

// Proteksi login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// 1. Logika Update Status Laporan
if (isset($_POST['update_status'])) {
    $id_laporan = $_POST['id_laporan'];
    $status_baru = $_POST['status_baru'];
    $query_update = "UPDATE laporan_kehilangan SET status_laporan = '$status_baru' WHERE id_laporan = '$id_laporan'";
    mysqli_query($conn, $query_update);
    header("Location: admin.php");
    exit;
}

// 2. Logika Hapus Laporan (Ditaruh di sini supaya bisa dipanggil kapan saja)
if (isset($_GET['hapus_laporan'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus_laporan']);
    $query_hapus = "DELETE FROM laporan_kehilangan WHERE id_laporan = '$id_hapus'";
    
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>alert('Laporan berhasil dihapus!'); window.location='admin.php';</script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($conn);
    }
}

// 3. Logika Simpan Barang Temuan
if (isset($_POST['submit_simpan'])) {
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $lokasi_peron = mysqli_real_escape_string($conn, $_POST['lokasi_peron']);
    $tanggal = $_POST['tanggal'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $id_petugas = $_SESSION['id_user'] ?? NULL; 

    $foto_name = $_FILES['foto_barang']['name'];
    $foto_tmp  = $_FILES['foto_barang']['tmp_name'];
    $ekstensi  = pathinfo($foto_name, PATHINFO_EXTENSION);
    $foto_baru = time() . "_" . str_replace(' ', '_', $nama_barang) . "." . $ekstensi;
    $target_dir = "uploads/" . $foto_baru;

    if (move_uploaded_file($foto_tmp, $target_dir)) {
        $query = "INSERT INTO barang_temuan (nama_barang, deskripsi, foto_barang, lokasi_temuan, lokasi_peron, tanggal_temuan, id_petugas, status) 
                  VALUES ('$nama_barang', '$deskripsi', '$foto_baru', '$lokasi', '$lokasi_peron', '$tanggal', '$id_petugas', 'tersedia')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Data & Foto berhasil disimpan!'); window.location='admin.php';</script>";
        } else {
            echo "Gagal database: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Gagal upload foto. Pastikan folder uploads/ sudah ada!');</script>";
    }
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
                    <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'barang_temuan.php') ? 'active' : '' ?>" href="barang_temuan.php">
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

            <div class="col-md-10 offset-md-2 p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold">Manajemen Lost & Found</h3>
                        <p class="text-muted">Kelola barang temuan dan laporan penumpang hari ini.</p>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold d-block">Selamat datang, <?= htmlspecialchars($_SESSION['username']); ?></span>
                        <small class="text-primary">Selamat bertugas</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card p-4 shadow-sm border-0">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Input Barang Baru</h5>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" placeholder="Misal: Dompet" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Lokasi Temuan</label>
                                    <select name="lokasi" class="form-select" required>
                                        <option value="" selected disabled>-- Pilih Lokasi --</option>
                                        <option value="Stasiun Serpong">Stasiun Serpong</option>
                                        <option value="Stasiun Tangerang">Stasiun Tangerang</option>
                                        <option value="Stasiun Bogor">Stasiun Bogor</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Lokasi Peron</label>
                                    <input type="text" name="lokasi_peron" class="form-control" placeholder="Misal: Peron 2" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Foto Barang</label>
                                    <input type="file" name="foto_barang" class="form-control" accept="image/*" required>
                                </div>
                                <button type="submit" name="submit_simpan" class="btn btn-primary w-100">Simpan ke Database</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card p-4 shadow-sm border-0">
                            <h5 class="fw-bold mb-4"><i class="fa-solid fa-list-check text-primary me-2"></i>Laporan Kehilangan</h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="small text-muted text-uppercase">
                                            <th>Pelapor</th>
                                            <th>Barang</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql_laporan = "SELECT l.*, u.nama 
                                                        FROM laporan_kehilangan l 
                                                        JOIN users u ON l.id_pelapor = u.id_user 
                                                        WHERE l.status_laporan != 'selesai' 
                                                        ORDER BY l.id_laporan DESC";
                                        $result_laporan = mysqli_query($conn, $sql_laporan);

                                        while ($row = mysqli_fetch_assoc($result_laporan)) {
                                        ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($row['nama']); ?></strong></td>
                                            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                            <td><?= htmlspecialchars($row['lokasi_hilang']); ?></td>
                                            <td>
                                                <form action="" method="POST">
                                                    <input type="hidden" name="id_laporan" value="<?= $row['id_laporan']; ?>">
                                                    <select name="status_baru" class="form-select form-select-sm" onchange="this.form.submit()">
                                                        <option value="mencari" <?= ($row['status_laporan'] == 'mencari') ? 'selected' : ''; ?>>Mencari</option>
                                                        <option value="cocok" <?= ($row['status_laporan'] == 'cocok') ? 'selected' : ''; ?>>Pencocokan</option>
                                                        <option value="selesai" <?= ($row['status_laporan'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                                                    </select>
                                                    <input type="hidden" name="update_status" value="1">
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <a href="admin.php?hapus_laporan=<?= $row['id_laporan']; ?>" 
                                                   class="btn btn-sm btn-outline-danger mb-1" 
                                                   onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>

                                                <?php if ($row['status_laporan'] == 'cocok'): ?>
                                                    <button type="button" class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#modalVerif<?= $row['id_laporan'] ?>">
                                                        <i class="fa-solid fa-paper-plane"></i> Verif
                                                    </button>
                                                    
                                                    <div class="modal fade" id="modalVerif<?= $row['id_laporan'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content text-dark">
                                                                <form action="proses_verifikasi.php" method="POST">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title fw-bold">Kirim Undangan Verifikasi</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body text-start">
                                                                        <p>Kirim notifikasi ke <strong><?= $row['nama'] ?></strong>?</p>
                                                                        <input type="hidden" name="id_laporan" value="<?= $row['id_laporan'] ?>">
                                                                        <input type="hidden" name="id_barang" value="<?= $row['id_barang_cocok'] ?? '' ?>"> 
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-bold">Pesan Instruksi</label>
                                                                            <textarea class="form-control" name="pesan_admin" rows="3" required placeholder="Contoh: Silahkan ke loket stasiun..."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" name="kirim_verif" class="btn btn-primary w-100">Kirim ke User</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
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