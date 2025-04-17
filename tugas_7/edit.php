<?php
require_once 'koneksi.php';
require_once 'produk.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$row = $result->fetch_assoc();
?>

<form method="POST" action="produk.php">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <input type="text" name="nama_produk" value="<?= $row['nama_produk'] ?>" required>
    <input type="number" name="harga" value="<?= $row['harga'] ?>" required>
    <input type="text" name="deskripsi" value="<?= $row['deskripsi'] ?>" required>
    <input type="number" name="stok" value="<?= $row['stok'] ?>" required>
    <input type="text" name="kategori" value="<?= $row['kategori'] ?>" required>
    <button type="submit" name="update">Update Produk</button>
</form>
