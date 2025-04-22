<?php
require_once 'koneksi_9.php';
session_start();

// Fungsi Menampilkan Produk dengan Filter
function getProducts($conn, $kategori = '') {
    if (!empty($kategori)) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE kategori = ?");
        $stmt->bind_param("s", $kategori);
    } else {
        $stmt = $conn->prepare("SELECT * FROM products");
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Fungsi Menambah Produk
function tambahProduk($conn, $nama, $harga, $deskripsi, $stok, $kategori, $gambar) {
    $gambarName = '';
    
    if (isset($gambar) && $gambar['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($gambar['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
            return false;
        }
        
        $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION);
        $gambarName = time() . '_' . uniqid() . '.' . $ext;
        $targetDir = 'uploads/';
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        if (!move_uploaded_file($gambar['tmp_name'], $targetDir . $gambarName)) {
            $_SESSION['error'] = "Gagal mengunggah gambar.";
            return false;
        }
    } else {
        $_SESSION['error'] = "Gambar produk wajib diunggah.";
        return false;
    }
    
    $sql = "INSERT INTO products (nama_produk, harga, deskripsi, stok, kategori, gambar) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssss", $nama, $harga, $deskripsi, $stok, $kategori, $gambarName);
    
    return $stmt->execute();
}

// Fungsi Update Produk
function updateProduct($conn, $id, $nama, $harga, $deskripsi, $stok, $kategori, $gambar) {
    if (isset($gambar) && $gambar['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($gambar['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
            return false;
        }
        
        $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION);
        $gambarName = time() . '_' . uniqid() . '.' . $ext;
        $targetDir = 'uploads/';
        
        if (!move_uploaded_file($gambar['tmp_name'], $targetDir . $gambarName)) {
            $_SESSION['error'] = "Gagal mengunggah gambar.";
            return false;
        }
        
        $sql = "UPDATE products SET nama_produk=?, harga=?, deskripsi=?, stok=?, kategori=?, gambar=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssssi", $nama, $harga, $deskripsi, $stok, $kategori, $gambarName, $id);
    } else {
        $sql = "UPDATE products SET nama_produk=?, harga=?, deskripsi=?, stok=?, kategori=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssi", $nama, $harga, $deskripsi, $stok, $kategori, $id);
    }
    
    return $stmt->execute();
}

// Fungsi Hapus Produk
function hapusProduk($conn, $id) {
    $sql = "SELECT gambar FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $sql = "DELETE FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if (!empty($row['gambar']) && file_exists('uploads/'.$row['gambar'])) {
            unlink('uploads/'.$row['gambar']);
        }
        return true;
    }
    return false;
}

// Handler CRUD Produk
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $gambar = $_FILES['gambar'] ?? null;
    
    if (tambahProduk($conn, $nama, $harga, $deskripsi, $stok, $kategori, $gambar)) {
        $_SESSION['success'] = "Produk berhasil ditambahkan!";
    }
    header("Location: index_10.php");
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $gambar = $_FILES['gambar'] ?? null;
    
    if (updateProduct($conn, $id, $nama, $harga, $deskripsi, $stok, $kategori, $gambar)) {
        $_SESSION['success'] = "Produk berhasil diupdate!";
    }
    header("Location: index_10.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    if (hapusProduk($conn, $id)) {
        $_SESSION['success'] = "Produk berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus produk";
    }
    header("Location: index_10.php");
    exit;
}
?>