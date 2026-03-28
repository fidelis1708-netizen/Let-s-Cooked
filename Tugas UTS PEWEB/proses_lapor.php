<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelapor = $_SESSION['id_user']; 
    $nama_barang = mysqli_real_escape_string($conn, $_POST['jenis_barang']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $tanggal = $_POST['tanggal'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $nama_file_db = NULL; 

    if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $ekstensi = pathinfo($_FILES["foto_barang"]["name"], PATHINFO_EXTENSION);
        $nama_file_db = "IMG_" . uniqid() . "." . $ekstensi;
        $target_file = $target_dir . $nama_file_db;

        move_uploaded_file($_FILES["foto_barang"]["tmp_name"], $target_file);
    }

    $query = "INSERT INTO laporan_kehilangan 
              (id_pelapor, nama_barang, deskripsi_ciri_khusus, lokasi_hilang, tanggal_hilang, status_laporan, foto_barang) 
              VALUES 
              ('$id_pelapor', '$nama_barang', '$deskripsi', '$lokasi', '$tanggal', 'mencari', '$nama_file_db')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Laporan berhasil dikirim!');
                window.location.href = 'home.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>