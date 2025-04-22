<?php
require_once 'koneksi_10.php';
session_start();

if (!isset($_SESSION['order_id'])) {
    header("Location: index_10.php");
    exit;
}

$order_id = $_SESSION['order_id'];
unset($_SESSION['order_id']);

// Ambil data order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Ambil items order
$stmt = $conn->prepare("
    SELECT oi.*, p.nama_produk 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold text-center mb-6">🎉 Order Berhasil!</h1>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Detail Order</h2>
                <p><strong>No. Order:</strong> #<?= $order['id'] ?></p>
                <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                <p><strong>Total:</strong> Rp <?= number_format($order['total'], 0, ',', '.') ?></p>
                <p><strong>Metode Pembayaran:</strong> <?= ucfirst($order['metode_pembayaran']) ?></p>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Produk yang Dibeli</h2>
                <ul class="divide-y divide-gray-200">
                    <?php while ($item = $order_items->fetch_assoc()): ?>
                        <li class="py-3 flex justify-between">
                            <span><?= $item['quantity'] ?> × <?= htmlspecialchars($item['nama_produk']) ?></span>
                            <span>Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total Pembayaran</span>
                    <span>Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <a href="index_10.php" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>