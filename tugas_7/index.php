<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manajemen Produk</title>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-6 text-blue-600">Tambah Produk</h1>
        
        <form method="POST" action="produk.php" class="mb-8">
            <input type="hidden" name="id">
            <div class="mb-4">
                <label class="block font-bold mb-2 text-gray-700">Nama Produk:</label>
                <input type="text" name="nama_produk" class="border border-gray-300 p-2 w-full rounded-md focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-2 text-gray-700">Harga:</label>
                <input type="number" name="harga" class="border border-gray-300 p-2 w-full rounded-md focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-2 text-gray-700">Deskripsi:</label>
                <input type="text" name="deskripsi" class="border border-gray-300 p-2 w-full rounded-md focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-2 text-gray-700">Stok:</label>
                <input type="number" name="stok" class="border border-gray-300 p-2 w-full rounded-md focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block font-bold mb-2 text-gray-700">Kategori:</label>
                <input type="text" name="kategori" class="border border-gray-300 p-2 w-full rounded-md focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" name="tambah" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Tambah Produk</button>
        </form>

        <h1 class="text-3xl font-bold mb-6 text-blue-600">Daftar Produk</h1>
        <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden shadow-lg">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Nama Produk</th>
                    <th class="border border-gray-300 p-2">Harga</th>
                    <th class="border border-gray-300 p-2">Deskripsi</th>
                    <th class="border border-gray-300 p-2">Stok</th>
                    <th class="border border-gray-300 p-2">Kategori</th>
                    <th class="border border-gray-300 p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once 'koneksi.php';
                require_once 'produk.php';

                $result = getProducts($conn);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='bg-gray-100 hover:bg-gray-200'>
                        <td class='border border-gray-300 p-2'>{$row['id']}</td>
                        <td class='border border-gray-300 p-2'>{$row['nama_produk']}</td>
                        <td class='border border-gray-300 p-2'>{$row['harga']}</td>
                        <td class='border border-gray-300 p-2'>{$row['deskripsi']}</td>
                        <td class='border border-gray-300 p-2'>{$row['stok']}</td>
                        <td class='border border-gray-300 p-2'>{$row['kategori']}</td>
                        <td class='border border-gray-300 p-2'>
                            <a href='edit.php?id={$row['id']}' class='text-green-500'>Edit</a> | 
                            <a href='produk.php?hapus={$row['id']}' class='text-red-500' onclick='return confirm(\"Hapus produk ini?\")'>Hapus</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
