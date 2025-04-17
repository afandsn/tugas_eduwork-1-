<?php
include 'koneksi.php';

// Ambil kategori dari URL jika ada
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Query produk
$sql = "SELECT * FROM produk";
if ($kategori) {
    $sql .= " WHERE kategori = '$kategori'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Toko Online</title>
    <style>
        .produk { display: inline-block; width: 200px; margin: 10px; border: 1px solid #ddd; padding: 10px; }
    </style>
</head>
<body>
    <h2>Daftar Produk</h2>

    <!-- Filter Kategori -->
    <form method="GET">
        <select name="kategori" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <option value="Elektronik" <?= ($kategori == 'Elektronik') ? 'selected' : '' ?>>Elektronik</option>
            <option value="Aksesoris" <?= ($kategori == 'Aksesoris') ? 'selected' : '' ?>>Aksesoris</option>
            <option value="Fashion" <?= ($kategori == 'Fashion') ? 'selected' : '' ?>>Fashion</option>
        </select>
    </form>

    <!-- Daftar Produk -->
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="produk">
            <img src="images/<?= $row['gambar'] ?>" width="150">
            <h3><?= $row['nama'] ?></h3>
            <p>Kategori: <?= $row['kategori'] ?></p>
            <p>Harga: Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
        </div>
    <?php } ?>

</body>
</html>
