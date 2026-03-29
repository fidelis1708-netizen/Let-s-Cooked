<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "lostnf");

if (isset($_POST['kirim_verif'])) {
    $id_laporan = $_POST['id_laporan'];
    $id_barang  = $_POST['id_barang'];
    $pesan      = mysqli_real_escape_string($conn, $_POST['pesan_admin']);
    $id_petugas = $_SESSION['id_user'] ?? NULL; 
    $status_verifikasi = 'pending';

    $query_klaim = "INSERT INTO klaim_pencocokan (id_barang, id_laporan, id_petugas, bukti_kepemilikan, status_verifikasi) 
                    VALUES ('$id_barang', '$id_laporan', '$id_petugas', '$pesan', '$status_verifikasi')";

    $query_update_laporan = "UPDATE laporan_kehilangan SET status_laporan = 'proses' WHERE id_laporan = '$id_laporan'";

    if (mysqli_query($conn, $query_klaim) && mysqli_query($conn, $query_update_laporan)) {
        echo "<script>
                alert('Undangan verifikasi berhasil dikirim!');
                window.location='admin.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: admin.php");
    exit;
}
?>