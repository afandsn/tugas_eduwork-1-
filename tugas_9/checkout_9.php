<?php
require_once 'koneksi_9.php';
require_once 'cart_9.php';
session_start();

$cart_items = getCartItems($conn);
$grand_total = getCartTotal($conn);

if ($cart_items->num_rows === 0) {
    header("Location: cart_10.php");
    exit;
}

// Generate kode order unik
$order_code = 'ORD-' . strtoupper(uniqid());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8">Checkout</h1>
            
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Kode Order: <?= $order_code ?></h2>
                </div>
                
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Pesanan</h3>
                    <div class="space-y-4">
                        <?php while ($item = $cart_items->fetch_assoc()): 
                            $total = $item['harga'] * $item['quantity'];
                        ?>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <img src="uploads/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama_produk']) ?>" class="w-12 h-12 rounded-md object-cover">
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['nama_produk']) ?></h4>
                                        <p class="text-sm text-gray-500"><?= $item['quantity'] ?> x Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Rp <?= number_format($total, 0, ',', '.') ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <span class="text-base font-medium text-gray-900">Subtotal</span>
                            <span class="text-base font-medium text-gray-900">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-base font-medium text-gray-900">Total</span>
                            <span class="text-xl font-bold text-gray-900">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengiriman</h3>
                    <form method="POST" action="process_checkout_10.php">
                        <input type="hidden" name="order_code" value="<?= $order_code ?>">
                        <input type="hidden" name="total" value="<?= $grand_total ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required></textarea>
                            </div>
                            
                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input type="tel" id="telepon" name="telepon" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            
                            <div>
                                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                <select id="metode_pembayaran" name="metode_pembayaran" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="cod">COD (Bayar di Tempat)</option>
                                    <option value="e-wallet">E-Wallet</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <button type="submit" class="w-full bg-green-500 text-white py-3 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-credit-card mr-2"></i> Proses Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>