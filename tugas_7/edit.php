<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $produk = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    // Cek apakah ada gambar baru di-upload
    if ($_FILES['gambar']['name'] != '') {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "uploads/" . $gambar);
        $query = "UPDATE products SET nama_produk='$nama', harga='$harga', deskripsi='$deskripsi', stok='$stok', gambar='$gambar' WHERE id=$id";
    } else {
        $query = "UPDATE products SET nama_produk='$nama', harga='$harga', deskripsi='$deskripsi', stok='$stok' WHERE id=$id";
    }

    mysqli_query($conn, $query);
    header("Location: index.php");
}
?>

<h2>Edit Produk</h2>
<form method="POST" enctype="multipart/form-data">
    Nama Produk: <input type="text" name="nama_produk" value="<?php echo $produk['nama_produk']; ?>"><br>
    Harga: <input type="text" name="harga" value="<?php echo $produk['harga']; ?>"><br>
    Deskripsi: <textarea name="deskripsi"><?php echo $produk['deskripsi']; ?></textarea><br>
    Stok: <input type="number" name="stok" value="<?php echo $produk['stok']; ?>"><br>
    
    Gambar Produk (kosongkan jika tidak ganti): 
    <input type="file" name="gambar"><br>
    <img src="uploads/<?php echo $produk['gambar']; ?>" width="100"><br>

    <input type="submit" name="update" value="Update">
</form>
