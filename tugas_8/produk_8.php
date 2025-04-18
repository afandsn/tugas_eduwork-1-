<?php
require_once 'koneksi_8.php';
session_start();

// Fungsi Menampilkan Produk
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

// Fungsi Menambah Produk dengan Gambar
function tambahProduk($conn, $nama, $harga, $deskripsi, $stok, $kategori, $gambar) {
    $gambarName = '';
    
    if (isset($gambar) && $gambar['error'] == 0) {
        // Validasi gambar
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($gambar['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
            return false;
        }
        
        // Generate nama unik untuk gambar
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
    
    // Gunakan prepared statement
    $sql = "INSERT INTO products (nama_produk, harga, deskripsi, stok, kategori, gambar) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssss", $nama, $harga, $deskripsi, $stok, $kategori, $gambarName);
    
    return $stmt->execute();
}

// Fungsi Update Produk dengan Gambar
function updateProduct($conn, $id, $nama, $harga, $deskripsi, $stok, $kategori, $gambar) {
    // Jika ada gambar baru diunggah
    if (isset($gambar) && $gambar['error'] == 0) {
        // Validasi gambar
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($gambar['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
            return false;
        }
        
        // Generate nama unik untuk gambar
        $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION);
        $gambarName = time() . '_' . uniqid() . '.' . $ext;
        $targetDir = 'uploads/';
        
        if (!move_uploaded_file($gambar['tmp_name'], $targetDir . $gambarName)) {
            $_SESSION['error'] = "Gagal mengunggah gambar.";
            return false;
        }
        
        // Update termasuk gambar baru
        $sql = "UPDATE products SET nama_produk=?, harga=?, deskripsi=?, stok=?, kategori=?, gambar=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssssi", $nama, $harga, $deskripsi, $stok, $kategori, $gambarName, $id);
    } else {
        // Update tanpa mengubah gambar
        $sql = "UPDATE products SET nama_produk=?, harga=?, deskripsi=?, stok=?, kategori=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssi", $nama, $harga, $deskripsi, $stok, $kategori, $id);
    }
    
    return $stmt->execute();
}

// Fungsi Hapus Produk
function hapusProduk($conn, $id) {
    // Pertama dapatkan nama gambar untuk menghapus dari server
    $sql = "SELECT gambar FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Hapus produk
    $sql = "DELETE FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Hapus file gambar jika ada
        if (!empty($row['gambar']) && file_exists('uploads/'.$row['gambar'])) {
            unlink('uploads/'.$row['gambar']);
        }
        return true;
    }
    return false;
}

// Handler untuk aksi tambah produk
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $gambar = $_FILES['gambar'] ?? null;
    
    if (tambahProduk($conn, $nama, $harga, $deskripsi, $stok, $kategori, $gambar)) {
        $_SESSION['success'] = "Produk berhasil ditambahkan!";
        header("Location: index_10.php");
        exit;
    } else {
        header("Location: index_10.php");
        exit;
    }
}

// Handler untuk aksi update produk
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
        header("Location: index_10.php");
        exit;
    } else {
        header("Location: edit_10.php?id=".$id);
        exit;
    }
}

// Handler untuk aksi hapus produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    if (hapusProduk($conn, $id)) {
        $_SESSION['success'] = "Produk berhasil dihapus!";
        header("Location: index_10.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal menghapus produk";
        header("Location: index_10.php");
        exit;
    }
}
?>