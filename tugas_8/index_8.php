<?php
require_once 'koneksi_8.php';
require_once 'produk_8.php';
session_start();

// Ambil parameter kategori dari URL
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Ambil data produk
$produk = getProducts($conn, $kategori_filter);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Toko Online Kami</h1>
        
        <!-- Tampilkan pesan sukses/error -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Filter Kategori -->
        <div class="mb-8 bg-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Filter Kategori</h2>
            <div class="flex flex-wrap gap-2">
                <a href="index_10.php" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Semua</a>
                <?php
                // Ambil semua kategori unik
                $kategori_query = $conn->query("SELECT DISTINCT kategori FROM products");
                while ($kategori = $kategori_query->fetch_assoc()):
                ?>
                    <a href="index_10.php?kategori=<?= urlencode($kategori['kategori']) ?>" 
                       class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= ($kategori_filter == $kategori['kategori']) ? 'bg-blue-200' : '' ?>">
                       <?= htmlspecialchars($kategori['kategori']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
        
        <!-- Tombol Tambah Produk -->
        <div class="mb-4">
            <a href="tambah_10.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                + Tambah Produk Baru
            </a>
        </div>
        
        <!-- Daftar Produk -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if ($produk->num_rows > 0): ?>
                <?php while ($row = $produk->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" 
                             alt="<?= htmlspecialchars($row['nama_produk']) ?>" 
                             class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-xl mb-2"><?= htmlspecialchars($row['nama_produk']) ?></h3>
                            <p class="text-gray-600 mb-2"><?= htmlspecialchars($row['deskripsi']) ?></p>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-lg">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    <?= htmlspecialchars($row['kategori']) ?>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm">Stok: <?= $row['stok'] ?></span>
                                <div class="flex gap-2">
                                    <a href="edit_10.php?id=<?= $row['id'] ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <a href="produk_10.php?hapus=<?= $row['id'] ?>" 
                                       class="text-red-500 hover:text-red-700"
                                       onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-8">
                    <p class="text-gray-500">Tidak ada produk yang ditemukan</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>