<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_laporan = mysqli_real_escape_string($conn, $_GET['id']);

    $sql_update_laporan = "UPDATE laporan_kehilangan SET status_laporan = 'selesai' WHERE id_laporan = '$id_laporan'";
    
    n_kehilangan ada kolom yang isinya ID dari barang_temuan
    $sql_update_barang = "UPDATE barang_temuan SET status = 'Selesai' 
                          WHERE id_barang = (SELECT id_barang FROM laporan_kehilangan WHERE id_laporan = '$id_laporan')";

    mysqli_query($conn, $sql_update_laporan);

    if (mysqli_query($conn, $sql_update_barang)) {
        echo "<script>
                alert('Terima kasih! Barang telah berhasil dikembalikan.');
                window.location.href = 'home.php';
              </script>";
    } else {
        echo "Error detail: " . mysqli_error($conn);
    }
} else {
    header("Location: home.php");
}
?>