<?php
require_once 'koneksi_8.php';
require_once 'produk_8.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Tambah Produk Baru</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="produk_10.php" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="nama_produk" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="harga" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" class="w-full p-2 border rounded" required></textarea>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stok" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="kategori" class="w-full p-2 border rounded" required>
                <option value="">Pilih Kategori</option>
                <?php
                $kategori_list = $conn->query("SELECT DISTINCT kategori FROM products");
                while ($kat = $kategori_list->fetch_assoc()):
                ?>
                    <option value="<?= htmlspecialchars($kat['kategori']) ?>">
                        <?= htmlspecialchars($kat['kategori']) ?>
                    </option>
                <?php endwhile; ?>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
            <input type="file" name="gambar" class="w-full p-2 border rounded bg-white" required>
            <p class="text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
        </div>
        
        <div class="flex justify-between pt-4">
            <a href="index_10.php" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Kembali</a>
            <button type="submit" name="tambah" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Tambah Produk</button>
        </div>
    </form>
</div>
</body>
</html>