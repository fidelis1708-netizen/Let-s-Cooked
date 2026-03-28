<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['update'])) {
    $id_user = $_SESSION['id_user'];
    
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    // Update database
    $query = "UPDATE users SET 
              nama = '$nama', 
              email = '$email', 
              no_telp = '$no_telp' 
              WHERE id_user = '$id_user'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['username'] = $nama;
        
        echo "<script>
                alert('Profil berhasil diperbarui!');
                window.location.href = 'profile.php';
              </script>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    header("Location: profile.php");
}
?>