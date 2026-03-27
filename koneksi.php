<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lostnf"; // Sesuai nama database di gambar Anda

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>