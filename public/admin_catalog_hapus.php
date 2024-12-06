<?php
require 'Database.php'; // Pastikan file ini terhubung ke database Anda

header('Content-Type: application/json');

// Validasi input ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID produk tidak valid!'
    ]);
    exit;
}

$id = intval($_GET['id']); // Konversi ke integer

// Ambil data produk untuk mendapatkan informasi gambar
$query = "SELECT gambar FROM produk WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $gambar = $row['gambar']; // Nama file gambar

    // Hapus file gambar dari folder
    $gambarPath = __DIR__ . '/AsetFoto/Catalog/' . $gambar; // Sesuaikan path folder Anda
    if (file_exists($gambarPath)) {
        unlink($gambarPath);
    }

    // Hapus data produk dari database
    $deleteQuery = "DELETE FROM produk WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $id);
    if ($deleteStmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Produk berhasil dihapus!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menghapus produk dari database!'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Produk tidak ditemukan!'
    ]);
}
?>