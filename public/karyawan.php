<?php
header('Content-Type: application/json');

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Koneksi ke database
include 'Database.php';

// Cek koneksi ke database
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Query untuk mengambil data penjualan
$sql = "SELECT tanggal, nama, stok FROM penjualan ORDER BY tanggal ASC";
$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit;
}

// Cek apakah ada data
if ($result->num_rows === 0) {
    echo json_encode(["message" => "No records found"]);
    exit;
}

// Ambil data ke dalam array
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Mengembalikan data dalam format JSON
echo json_encode($data);

// Menutup koneksi
$conn->close();
?>