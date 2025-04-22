<?php
require_once 'koneksi_9.php';
require_once 'produk_9.php';
session_start();

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    $_SESSION['error'] = "Produk tidak ditemukan";
    header("Location: index_10.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-blue-600">Edit Produk</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="produk_10.php" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="nama_produk" value="<?= htmlspecialchars($row['nama_produk']) ?>" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Harga</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($row['harga']) ?>" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" class="w-full p-2 border rounded" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stok" value="<?= htmlspecialchars($row['stok']) ?>" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <input type="text" name="kategori" value="<?= htmlspecialchars($row['kategori']) ?>" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
            <?php if (!empty($row['gambar'])): ?>
                <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" width="200" class="mb-2 border p-1">
            <?php else: ?>
                <p>Tidak ada gambar</p>
            <?php endif; ?>
            
            <label class="block text-sm font-medium text-gray-700">Gambar Baru (biarkan kosong jika tidak ingin mengubah)</label>
            <input type="file" name="gambar" class="w-full p-2 border rounded bg-white">
            <p class="text-xs text-gray-500">Format: JPG, PNG, GIF (Maks. 2MB)</p>
        </div>
        
        <div class="flex justify-between pt-4">
            <a href="index_10.php" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Kembali</a>
            <button type="submit" name="update" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Update Produk</button>
        </div>
    </form>
</div>
</body>
</html>