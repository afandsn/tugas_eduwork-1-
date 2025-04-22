<?php
require_once 'koneksi_9.php';
require_once 'produk_9.php';
require_once 'cart_9.php';

// Mulai session hanya jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    
    // Generate session ID jika belum ada
    if (!isset($_SESSION['session_id'])) {
        $_SESSION['session_id'] = session_id();
    }
}

// Ambil parameter kategori dari URL
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Query produk dengan atau tanpa filter
$produk = getProducts($conn, $kategori_filter);

// Hitung jumlah item di keranjang
$cart_count = 0;
$stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE session_id = ?");
if ($stmt) {
    $stmt->bind_param("s", $_SESSION['session_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $cart_count = $row['total'] ?? 0;
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">Toko Online Kami</h1>
            <div class="flex items-center space-x-4">
                <a href="cart_9.php" class="relative">
                    <i class="fas fa-shopping-cart text-2xl text-gray-700"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            <?= $cart_count ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a href="admin_9.php" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                    Admin Panel
                </a>
            </div>
        </div>
        
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
                <a href="index_9.php" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= empty($kategori_filter) ? 'bg-blue-200' : '' ?>">Semua</a>
                <?php
                // Ambil semua kategori unik
                $kategori_query = $conn->query("SELECT DISTINCT kategori FROM products");
                while ($kategori = $kategori_query->fetch_assoc()):
                ?>
                    <a href="index_9.php?kategori=<?= urlencode($kategori['kategori']) ?>" 
                       class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 <?= ($kategori_filter == $kategori['kategori']) ? 'bg-blue-200' : '' ?>">
                       <?= htmlspecialchars($kategori['kategori']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
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
                            <div class="text-sm text-gray-500 mb-3">Stok: <?= $row['stok'] ?></div>
                            
                            <form method="POST" action="cart_9.php" class="mt-2">
                                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                <div class="flex items-center">
                                    <input type="number" name="quantity" value="1" min="1" max="<?= $row['stok'] ?>" 
                                           class="w-16 px-2 py-1 border rounded">
                                    <button type="submit" name="add_to_cart" 
                                            class="ml-2 flex-1 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-cart-plus mr-2"></i> Tambah
                                    </button>
                                </div>
                            </form>
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