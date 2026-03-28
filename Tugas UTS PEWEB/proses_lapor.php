<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelapor = $_SESSION['id_user']; 
    $nama_barang = $_POST['jenis_barang']; 
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $tanggal = $_POST['tanggal'];
    $status = "mencari"; 

    $query = "INSERT INTO laporan_kehilangan (id_pelapor, nama_barang, deskripsi_ciri_khusus, lokasi_hilang, tanggal_hilang, status_laporan) 
              VALUES ('$id_pelapor', '$nama_barang', '$deskripsi', '$lokasi', '$tanggal', '$status')";

    if (mysqli_query($conn, $query)) {
        header("Location: home.php?pesan=berhasil");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>