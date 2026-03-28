<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "lostnf");

// Logika Hapus Laporan Kehilangan
if (isset($_GET['hapus_laporan'])) {
    // Mengambil ID dari URL
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus_laporan']);
    
    // Query hapus berdasarkan id_laporan
    $query_hapus = "DELETE FROM laporan_kehilangan WHERE id_laporan = '$id_hapus'";
    
    if (mysqli_query($conn, $query_hapus)) {
        echo "<script>
                alert('Laporan berhasil dihapus!');
                window.location.href='admin.php';
              </script>";
    } else {
        echo "<script>alert('Gagal menghapus: " . mysqli_error($conn) . "');</script>";
    }
}

// Cek jika tombol simpan ditekan
if (isset($_POST['submit_simpan'])) {
    $nama_barang = $_POST['nama_barang'];
    $lokasi = $_POST['lokasi'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];
    
    // Karena di SQL anda id_petugas adalah FK, kita gunakan ID 1 sebagai contoh
    // Pastikan di tabel users sudah ada id_user = 1
    $id_petugas = 1; 

    $query = "INSERT INTO barang_temuan (nama_barang, deskripsi, lokasi_temuan, tanggal_temuan, id_petugas, status) 
              VALUES ('$nama_barang', '$deskripsi', '$lokasi', '$tanggal', '$id_petugas', 'tersedia')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='admin.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($conn);
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

    <link rel="stylesheet" href="desain/admin.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 d-none d-md-block sidebar p-4 position-fixed">
                <div class="text-center mb-5">
                    <i class="fa-solid fa-train-subway fa-3x mb-2 text-white"></i>
                    <h5 class="fw-bold">CommuterLink</h5>
                    <small class="opacity-75">Admin Panel</small>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fa-solid fa-house me-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa-solid fa-box-open me-2"></i> Barang Temuan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa-solid fa-clipboard-list me-2"></i> Laporan Hilang</a>
                    </li>
                    <hr class="text-white-50">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#"><i class="fa-solid fa-power-off me-2"></i> Keluar</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-10 offset-md-2 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold">Manajemen Lost & Found</h3>
                        <p class="text-muted">Kelola barang temuan dan laporan penumpang hari ini.</p>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold d-block">Admin Petugas</span>
                        <small class="text-primary">Stasiun Gambir</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Input
                                Barang Baru</h5>

                            <form action="" method="POST"> 
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" placeholder="Misal: Dompet Kulit Cokelat" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Lokasi Ditemukan</label>
                                    <input type="text" name="lokasi" class="form-control" placeholder="Misal: Peron 2" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Ciri-ciri khusus barang..."></textarea>
                                </div>
                                
                                <button type="submit" name="submit_simpan" class="btn btn-primary w-100 py-2 mt-2">
                                    Simpan ke Database
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-8 mb-4">
                        <div class="card p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold"><i class="fa-solid fa-list-check text-primary me-2"></i>Laporan
                                    Kehilangan</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="small text-muted text-uppercase">
                                            <th>Pelapor</th>
                                            <th>Nama Barang</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query untuk mengambil data laporan kehilangan dan nama pelapor dari tabel users
                                        $sql_laporan = "SELECT l.*, u.nama 
                                                        FROM laporan_kehilangan l 
                                                        JOIN users u ON l.id_pelapor = u.id_user 
                                                        ORDER BY l.id_laporan DESC";
                                        
                                        $result_laporan = mysqli_query($conn, $sql_laporan);

                                        // Loop untuk menampilkan setiap baris data
                                        while ($row = mysqli_fetch_assoc($result_laporan)) {
                                            // Menentukan warna badge berdasarkan status
                                            $status = $row['status_laporan'];
                                            $badge_class = 'bg-warning text-dark'; // Default untuk 'mencari'
                                            if ($status == 'cocok') $badge_class = 'bg-info text-white';
                                            if ($status == 'selesai') $badge_class = 'bg-success text-white';
                                        ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                            <td><?php echo htmlspecialchars($row['lokasi_hilang']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $badge_class; ?> status-badge">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="admin.php?hapus_laporan=<?php echo $row['id_laporan']; ?>" 
                                                class="btn btn-sm btn-light border text-danger" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus laporan dari <?php echo $row['nama']; ?>?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                        } // Akhir while 
                                        ?>
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