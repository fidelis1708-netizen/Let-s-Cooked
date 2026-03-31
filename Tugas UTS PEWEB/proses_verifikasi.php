<?php
session_start();
include 'koneksi.php';

// 1. Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_laporan = mysqli_real_escape_string($conn, $_GET['id']);

    $sql_update = "UPDATE laporan_kehilangan SET status_laporan = 'selesai' WHERE id_laporan = '$id_laporan'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>
                alert('Terima kasih! Laporan Anda telah diselesaikan.');
                window.location.href = 'home.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: home.php");
}
?>