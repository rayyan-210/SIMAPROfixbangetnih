<?php
require 'Database.php';

// Hapus gambar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $uploadDir = "./AsetFoto/carousel/";

    // Ambil nama file gambar berdasarkan ID
    $stmt_select = $conn->prepare("SELECT gambar FROM promosi WHERE id_promosi = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($row = $result->fetch_assoc()) {
        $fileName = $row['gambar'];
        $filePath = $uploadDir . $fileName;

        // Hapus data dari database
        $stmt_delete = $conn->prepare("DELETE FROM promosi WHERE id_promosi = ?");
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            // Cek apakah file gambar ada di folder
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    echo json_encode(["success" => true, "message" => "Gambar berhasil dihapus dari database dan folder."]);
                } else {
                    echo json_encode(["success" => false, "message" => "Gambar dihapus dari database, tetapi gagal dihapus dari folder."]);
                }
            } else {
                echo json_encode(["success" => true, "message" => "Gambar dihapus dari database, tetapi file tidak ditemukan di folder."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menghapus gambar dari database."]);
        }

        $stmt_delete->close();
    } else {
        echo json_encode(["success" => false, "message" => "Gambar tidak ditemukan."]);
    }

    $stmt_select->close();
} else {
    echo json_encode(["success" => false, "message" => "ID tidak valid atau tidak diberikan."]);
}
?>