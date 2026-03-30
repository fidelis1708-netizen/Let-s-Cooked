<?php
session_start();
include 'koneksi.php';

// 1. Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// 2. Ambil ID laporan dari URL
if (isset($_GET['id'])) {
    $id_laporan = mysqli_real_escape_string($conn, $_GET['id']);

    // 3. Update status laporan menjadi 'selesai'
    $sql_update = "UPDATE laporan_kehilangan SET status_laporan = 'selesai' WHERE id_laporan = '$id_laporan'";

    if (mysqli_query($conn, $sql_update)) {
        // 4. Jika berhasil, lempar balik ke home.php dengan pesan sukses
        echo "<script>
                alert('Terima kasih! Laporan Anda telah diselesaikan.');
                window.location.href = 'home.php';
              </script>";
    } else {
        // Jika gagal karena error database
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika ID tidak ditemukan di URL, balikkan ke home
    header("Location: home.php");
}
?>