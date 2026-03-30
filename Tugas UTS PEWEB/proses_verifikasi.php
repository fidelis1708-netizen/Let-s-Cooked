<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_laporan = $_POST['id_laporan'];
    $id_barang = $_POST['id_barang'];
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan_admin']);

    // 1. Update id_barang_cocok langsung di tabel laporan_kehilangan
    $query = "UPDATE laporan_kehilangan SET 
              id_barang_cocok = '$id_barang', 
              status_laporan = 'cocok' 
              WHERE id_laporan = '$id_laporan'";

    if (mysqli_query($conn, $query)) {
        // 2. Update status barang temuan menjadi 'diproses' agar tidak muncul di laporan lain
        mysqli_query($conn, "UPDATE barang_temuan SET status = 'diproses' WHERE id_barang = '$id_barang'");
        
        echo "<script>alert('Verifikasi terkirim ke pelapor!'); window.location='admin.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>