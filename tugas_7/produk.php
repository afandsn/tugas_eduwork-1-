<?php
include 'koneksi.php';

// Ambil filter kategori dari URL
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$query = "SELECT * FROM products";

if ($kategori != '') {
    $query .= " WHERE kategori = '$kategori'";
}

$result = mysqli_query($conn, $query);
?>

<h2>Daftar Produk</h2>

<!-- FORM FILTER KATEGORI -->
<form method="GET" action="">
    <label>Filter Kategori:</label>
    <select name="kategori">
        <option value="">-- Semua Kategori --</option>
        <option value="makanan" <?php if($kategori=='makanan') echo 'selected'; ?>>Makanan</option>
        <option value="aksesoris" <?php if($kategori=='aksesoris') echo 'selected'; ?>>Aksesoris</option>
        <!-- Tambah opsi sesuai kategori yang kamu punya -->
    </select>
    <input type="submit" value="Filter">
</form>

<!-- TAMPILKAN PRODUK -->
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div style="border:1px solid #ddd; padding:10px; margin:10px; width:300px;">
        <h3><?php echo $row['nama_produk']; ?></h3>

        <!-- TAMPILKAN GAMBAR -->
        <?php if (!empty($row['gambar'])) { ?>
            <img src="uploads/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_produk']; ?>" style="width:100%; max-height:200px; object-fit:cover;">
        <?php } else { ?>
            <p><i>Tidak ada gambar</i></p>
        <?php } ?>

        <p>Harga: Rp <?php echo number_format($row['harga']); ?></p>
        <p><?php echo $row['deskripsi']; ?></p>
        <p>Stok: <?php echo $row['stok']; ?></p>
        <p>Kategori: <?php echo $row['kategori']; ?></p>
    </div>
<?php } ?>
