<?php
require_once 'koneksi_9.php';
require_once 'cart_9.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simpan data order
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $total = $_POST['total'];
    
    // 1. Simpan data order
    $stmt = $conn->prepare("INSERT INTO orders (nama, email, alamat, telepon, metode_pembayaran, total) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $nama, $email, $alamat, $telepon, $metode_pembayaran, $total);
    $stmt->execute();
    $order_id = $conn->insert_id;
    
    // 2. Simpan order items
    $cart_items = getCartItems($conn);
    while ($item = $cart_items->fetch_assoc()) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, harga) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['harga']);
        $stmt->execute();
    }
    
    // 3. Kosongkan keranjang
    $conn->query("DELETE FROM cart");
    
    $_SESSION['order_id'] = $order_id;
    header("Location: order_success_10.php");
    exit;
}

header("Location: cart_10.php");
exit;
?>