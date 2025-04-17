<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$db = "toko_online"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal coba lagi: " . mysqli_connect_error());
}

$sql = "SHOW TABLES LIKE 'produk'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "Tabel produk ditemukan!";
} else {
    echo "Tabel produk tidak ditemukan!";
}

mysqli_close($conn);
?>
