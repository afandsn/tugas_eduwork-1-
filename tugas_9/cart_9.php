<?php
require_once 'koneksi_9.php';
session_start();

// Pastikan session_id ada
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}

// Fungsi Keranjang
function addToCart($conn, $product_id, $quantity = 1) {
    // Cek stok produk
    $stmt = $conn->prepare("SELECT stok FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if (!$product || $product['stok'] < $quantity) {
        $_SESSION['error'] = "Stok produk tidak mencukupi";
        return false;
    }
    
    // Cek apakah produk sudah ada di keranjang
    $stmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ? AND session_id = ?");
    $stmt->bind_param("is", $product_id, $_SESSION['session_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE product_id = ? AND session_id = ?");
        $stmt->bind_param("iis", $quantity, $product_id, $_SESSION['session_id']);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $_SESSION['session_id'], $product_id, $quantity);
    }
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Produk berhasil ditambahkan ke keranjang!";
        return true;
    }
    return false;
}

function getCartItems($conn) {
    $stmt = $conn->prepare("
        SELECT p.id, p.nama_produk, p.harga, p.gambar, p.stok, c.quantity, c.id as cart_id 
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        WHERE c.session_id = ?
    ");
    $stmt->bind_param("s", $_SESSION['session_id']);
    $stmt->execute();
    return $stmt->get_result();
}

function updateCartItem($conn, $cart_id, $quantity) {
    // Cek stok produk
    $stmt = $conn->prepare("
        SELECT p.stok 
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.id = ? AND c.session_id = ?
    ");
    $stmt->bind_param("is", $cart_id, $_SESSION['session_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if (!$product || $product['stok'] < $quantity) {
        $_SESSION['error'] = "Stok produk tidak mencukupi";
        return false;
    }
    
    if ($quantity <= 0) {
        return removeFromCart($conn, $cart_id);
    }
    
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND session_id = ?");
    $stmt->bind_param("iis", $quantity, $cart_id, $_SESSION['session_id']);
    return $stmt->execute();
}

function removeFromCart($conn, $cart_id) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND session_id = ?");
    $stmt->bind_param("is", $cart_id, $_SESSION['session_id']);
    return $stmt->execute();
}

function getCartTotal($conn) {
    $stmt = $conn->prepare("
        SELECT SUM(p.harga * c.quantity) as total 
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        WHERE c.session_id = ?
    ");
    $stmt->bind_param("s", $_SESSION['session_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

// Handler Aksi Keranjang
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;
    
    addToCart($conn, $product_id, $quantity);
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index_10.php'));
    exit;
}

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    
    if (updateCartItem($conn, $cart_id, $quantity)) {
        $_SESSION['success'] = "Keranjang berhasil diperbarui!";
    }
    header("Location: cart_10.php");
    exit;
}

if (isset($_GET['remove_from_cart'])) {
    $cart_id = $_GET['remove_from_cart'];
    
    if (removeFromCart($conn, $cart_id)) {
        $_SESSION['success'] = "Produk berhasil dihapus dari keranjang!";
    }
    header("Location: cart_10.php");
    exit;
}

if (isset($_GET['clear_cart'])) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $_SESSION['session_id']);
    $stmt->execute();
    
    $_SESSION['success'] = "Keranjang berhasil dikosongkan!";
    header("Location: cart_10.php");
    exit;
}
?>