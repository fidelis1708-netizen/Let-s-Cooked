<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kehilangan - Lost & Found KRL</title>
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="laporan.css">
</head>
<body>

<div class="app-container">
    <aside class="sidebar">
        <h2 class="logo">🔍 Lost & Found KRL</h2>
            <ul>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" onclick="location.href='home.php'">Dashboard</li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'laporan.php') ? 'active' : ''; ?>" onclick="location.href='laporan.php'">Laporkan Kehilangan</li>
            <li onclick="location.href='temuan.php'">Barang Temuan</li>
            <li onclick="location.href='profil.php'">Profil</li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="scroll-area">
            
            <div class="content-header">
                <div class="user-info">
                    <span>Selamat melaporkan, <strong><?php echo $_SESSION['username']; ?></strong></span>
                </div>
            </div>

            <div class="form-card">
                <div class="form-header">
                    <h2>Laporan Kehilangan</h2>
                    <p>Isi formulir berikut untuk melaporkan barang yang hilang di area KRL.</p>
                </div>

                <form action="proses_lapor.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group">
                        <label>Jenis Barang yang Hilang</label>
                        <input type="text" name="jenis_barang" placeholder="Contoh: Dompet, HP, Tas..." required>
                    </div>

                    <div class="input-group">
                        <label>Lokasi Kehilangan</label>
                        <select name="lokasi" required>
                            <option value="">-- Pilih Lokasi --</option>
                            <option>Stasiun Sudirman</option>
                            <option>Stasiun Manggarai</option>
                            <option>Stasiun Tanah Abang</option>
                            <option>Stasiun Bekasi</option>
                            <option>Stasiun Bogor</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Tanggal Kehilangan</label>
                        <input type="date" name="tanggal" required>
                    </div>

                    <div class="input-group">
                        <label>Deskripsi Barang</label>
                        <textarea name="deskripsi" placeholder="Contoh: Dompet coklat berisi KTP, ATM, uang..." required></textarea>
                    </div>

                    <div class="input-group">
                        <label>Foto Barang (Opsional)</label>
                        <div class="upload-area">
                            <input type="file" name="foto" id="file-upload" hidden>
                            <label for="file-upload" class="upload-label">
                                <span>📸</span>
                                <p>Klik untuk unggah foto barang</p>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Kirim Laporan Kehilangan</button>
                </form>
            </div>
        </div>
    </main>
</div>

</body>
</html>