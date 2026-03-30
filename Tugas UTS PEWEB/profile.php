<?php
session_start();
include 'koneksi.php';

// 1. CEK LOGIN (Penting agar tidak error saat akses langsung)
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// 2. AMBIL DATA USER TERBARU DARI DATABASE
$query = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query);

// 3. LOGIKA MEMBUAT INISIAL (Contoh: "Budi Utomo" jadi "BU")
$nama_awal = $user['nama'];
$inisial = strtoupper(substr($nama_awal, 0, 1)); 

// 4. PROSES UPDATE JIKA TOMBOL DIKLIK
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    $sql = "UPDATE users SET nama='$nama', email='$email', no_telp='$no_telp' WHERE id_user='$id_user'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profile.php';</script>";
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Lost & Found KRL</title>
    <link rel="stylesheet" href="desain/sidebar.css">
    <link rel="stylesheet" href="desain/profile.css">
</head>
<body>

    <div class="layout-wrapper">
        <aside class="sidebar">
            <h2 class="logo">🔍 Lost & Found KRL</h2>
            <ul>
                <li onclick="location.href='home.php'">Dashboard</li>
                <li onclick="location.href='laporan.php'">Laporkan Kehilangan</li>
                <li onclick="location.href='panduan.php'">Panduan Melaporkan</li>
                <li class="active" onclick="location.href='profile.php'">Profil</li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <span class="user-greeting">Selamat melaporkan, <strong><?php echo htmlspecialchars($user['nama']); ?></strong></span>
            </header>

            <div class="page-header">
                <h1>Profil Pengguna</h1>
                <p>Kelola informasi data diri Anda di sini.</p>
            </div>

            <div class="profile-container">
                <div class="profile-card">
                    <div class="profile-avatar"><?php echo $inisial; ?></div>
                    
                    <div class="profile-details">
                        <div class="info-group">
                            <label>Nama Lengkap</label>
                            <div class="info-value"><?php echo htmlspecialchars($user['nama']); ?></div>
                        </div>

                        <div class="info-group">
                            <label>Email</label>
                            <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>

                        <div class="info-group">
                            <label>Nomor Telepon</label>
                            <div class="info-value"><?php echo htmlspecialchars($user['no_telp'] ?? 'Belum diatur'); ?></div>
                        </div>

                        <button class="btn-edit" onclick="bukaModal()">Edit Profil</button>
                        <a href="logout.php" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');">Logout</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="modalEdit" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Edit Profil</h2>
                <span class="close-btn" onclick="tutupModal()">&times;</span>
            </div>
            
            <form action="" method="POST"> <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="no_telp" value="<?php echo htmlspecialchars($user['no_telp'] ?? ''); ?>" required>
                </div>
                <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        function bukaModal() { document.getElementById('modalEdit').style.display = 'flex'; }
        function tutupModal() { document.getElementById('modalEdit').style.display = 'none'; }
        window.onclick = function(event) {
            var modal = document.getElementById('modalEdit');
            if (event.target == modal) { modal.style.display = "none"; }
        }
    </script>
</body>
</html>