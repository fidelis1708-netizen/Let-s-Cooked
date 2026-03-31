<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_laporan = mysqli_real_escape_string($conn, $_GET['id']);
    $id_user = $_SESSION['id_user'];
    $tanggal_sekarang = date('Y-m-d');

    $query_data = mysqli_query($conn, "SELECT id_klaim, id_barang, id_petugas FROM klaim_pencocokan WHERE id_laporan = '$id_laporan'");
    $data = mysqli_fetch_assoc($query_data);
    
    $id_klaim = $data['id_klaim'];
    $id_barang = $data['id_barang'];
    $id_petugas = $data['id_petugas'];

    $insert_penyerahan = "INSERT INTO penyerahan (id_klaim, id_petugas, tanggal_penyerahan, keterangan) 
                          VALUES ('$id_klaim', '$id_petugas', '$tanggal_sekarang', 'Barang telah diambil oleh pemilik via sistem')";
    
    if (mysqli_query($conn, $insert_penyerahan)) {
        
        mysqli_query($conn, "UPDATE laporan_kehilangan SET status_laporan = 'selesai' WHERE id_laporan = '$id_laporan'");
        
        mysqli_query($conn, "UPDATE barang_temuan SET status = 'dikembalikan' WHERE id_barang = '$id_barang'");

        echo "<script>
                alert('Konfirmasi berhasil! Terima kasih telah menggunakan layanan CommuterLink.');
                window.location.href = 'home.php';
              </script>";
    }
}
?>