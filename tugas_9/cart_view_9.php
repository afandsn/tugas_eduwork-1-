<?php
require_once 'koneksi_9.php';
require_once 'cart_9.php';
session_start();

$cart_items = getCartItems($conn);
$grand_total = getCartTotal($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">Keranjang Belanja</h1>
            <a href="index_10.php" class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-1"></i> Lanjut Belanja
            </a>
        </div>
        
        <?php if (isset($_SESSION['cart_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?= $_SESSION['cart_message'] ?>
                <?php unset($_SESSION['cart_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($cart_items->num_rows > 0): ?>
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($item = $cart_items->fetch_assoc()): 
                            $total = $item['harga'] * $item['quantity'];
                        ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="uploads/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['nama_produk']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form method="POST" action="cart_10.php" class="flex">
                                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stok'] ?>" class="w-16 px-2 py-1 border rounded">
                                        <button type="submit" name="update_cart" class="ml-2 px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp <?= number_format($total, 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="cart_10.php?remove_from_cart=<?= $item['cart_id'] ?>" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Grand Total</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="flex justify-between items-center">
                <a href="cart_10.php?clear_cart" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                    <i class="fas fa-trash mr-1"></i> Kosongkan Keranjang
                </a>
                <a href="checkout_10.php" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    <i class="fas fa-credit-card mr-1"></i> Proses Checkout
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-shopping-cart text-5xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg mb-4">Keranjang belanja Anda kosong</p>
                <a href="index_10.php" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fas fa-arrow-left mr-1"></i> Mulai Belanja
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>