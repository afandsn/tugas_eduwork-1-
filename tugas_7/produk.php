<?php
require_once 'koneksi.php'; // Koneksi database

// Fungsi Menampilkan Produk
function getProducts($conn) {
    $sql = "SELECT * FROM products";
    return $conn->query($sql);
}

// Fungsi Menambah Produk
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];

    $sql = "INSERT INTO products (nama_produk, harga, deskripsi, stok, kategori) 
            VALUES ('$nama', '$harga', '$deskripsi', '$stok', '$kategori')";
    $conn->query($sql);
    header("Location: index.php");
}

// Fungsi Hapus Produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: index.php");
}

// Fungsi Update Produk
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];

    $sql = "UPDATE products SET nama_produk='$nama', harga='$harga', deskripsi='$deskripsi', stok='$stok', kategori='$kategori' WHERE id=$id";
    $conn->query($sql);
    header("Location: index.php");
}
?>
