<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simapro";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
<?php
// upload gambar
if (isset($_POST['uploadType'])) {
    $uploadType = $_POST['uploadType'];
    if ($uploadType === 'image') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $uploadDir = "C:/xampp/htdocs/SIMAPRO/public/AsetFoto/carousel/";
            $response = [];


            if ($file['error'] !== UPLOAD_ERR_OK) {
                exit(json_encode(["message" => "File upload error code: " . $file['error']]));
            }
            if (!in_array($file['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
                exit(json_encode(["message" => "Invalid file type. Only JPG, PNG, and GIF files are allowed."]));
            }

            // Buat direktori jika belum ada
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0777, true);

            // Tentukan nama file yang unik
            $fileName = basename($file["name"]);
            $filePath = $uploadDir . $fileName;
            $counter = 1;
            while (file_exists($filePath)) {
                $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . "_{$counter}." . pathinfo($fileName, PATHINFO_EXTENSION);
                $counter++;
            }

            // Simpan file dan data ke database
            if (move_uploaded_file($file["tmp_name"], $filePath)) {
                $stmt = $conn->prepare("INSERT INTO promosi (gambar, tanggal) VALUES (?, NOW())");
                if ($stmt) {
                    $stmt->bind_param("s", $fileName);
                    $result = $stmt->execute();
                    $stmt->close();
                    echo json_encode($result ? ["success" => true, "message" => "Image saved successfully"] : ["message" => "Failed to save to database"]);
                } else {
                    echo json_encode(["message" => "Database statement error"]);
                }
            } else {
                echo json_encode(["message" => "Failed to move uploaded file"]);
            }
        }
    }
}
?>

<?php
// excel ke database
if (isset($_POST['uploadType'])) {
    $uploadType = $_POST['uploadType'];
    if ($uploadType === 'csv') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];

            // Buka file CSV yang diunggah
            if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {

                // Ambil header dan trim spasi di sekitar nama kolom
                $header = array_map('trim', fgetcsv($handle, 1000, ','));

                // Periksa apakah header sesuai dengan kolom yang diharapkan
                $expected_header = ['tanggal', 'nama', 'stok'];
                if ($header !== $expected_header) {
                    die(json_encode(["status" => "error", "message" => "Header CSV tidak sesuai dengan kolom tabel."]));
                }

                // Membaca data CSV baris demi baris
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $data = array_map('trim', $data);
                    if (count($data) === 3) {

                        $sql = "INSERT INTO penjualan (tanggal, nama, stok) VALUES ('" . implode("', '", $data) . "')";

                        if ($conn->query($sql) !== TRUE) {
                            die(json_encode(["status" => "error", "message" => "Error menyisipkan data: " . $conn->error]));
                        }
                    } else {
                        die(json_encode(["status" => "error", "message" => "Data tidak lengkap: " . implode(", ", $data)]));
                    }
                }

                fclose($handle);
                echo json_encode(["status" => "success", "message" => "File berhasil dimasukkan ke dalam database."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error membuka file."]);
            }
        }
    }
}
?>
<?php
// membuat chart
if (isset($_GET['json']) && $_GET['json'] === 'true') {
    header('Content-Type: application/json');

    // Aktifkan error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $sql = "SELECT tanggal, nama, stok FROM penjualan ORDER BY tanggal ASC";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(["error" => "Query failed: " . $conn->error]);
        exit;
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }


    echo json_encode($data);
    $conn->close();
    exit;
}
?>

<?php
// Pastikan koneksi database sudah benar
if (isset($_POST['aksi']) && $_POST['aksi'] == 'hapus_semua') {

    // Query untuk mengosongkan tabel
    $query = "TRUNCATE TABLE penjualan";
    
    // Eksekusi query
    if (mysqli_query($conn, $query)) {
        echo json_encode([
            'status' => 'success', 
            'pesan' => 'Semua data berhasil dihapus'
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'pesan' => 'Gagal menghapus data: ' . mysqli_error($conn)
        ]);
    }
    
    exit();
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi untuk menangani upload gambar dan data produk
function uploadProduk() {
    global $conn;

    // Pastikan ada file yang diupload
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $uploadDir = "./AsetFoto/Catalog/";
        
        // Validasi dan simpan file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            exit(json_encode(["message" => "File upload error code: " . $file['error']]));
        }
    
        if (!in_array($file['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
            exit(json_encode(["message" => "Invalid file type. Only JPG, PNG, and GIF files are allowed."]));
        }
    
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    
        $fileName = basename($file["name"]);
        $filePath = $uploadDir . $fileName;
        $counter = 1;
        while (file_exists($filePath)) {
            $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . "_{$counter}." . pathinfo($fileName, PATHINFO_EXTENSION);
            $counter++;
        }
    
        if (move_uploaded_file($file["tmp_name"], $filePath)) {
            $namaProduk = $_POST['namaProduk'];
            $kodeProduk = $_POST['kodeProduk'];
            $jenisProduk = $_POST['jenisProduk'];
            $harga = $_POST['harga'];
            $fileNameForDB = basename($filePath);
    
            $stmt = $conn->prepare("INSERT INTO produk (id, kodeproduk, nama, gambar, harga, jenis) VALUES ('', ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssis", $kodeProduk, $namaProduk, $fileNameForDB, $harga, $jenisProduk); // s: string, i: integer
                $result = $stmt->execute();
                $stmt->close();
    
                echo json_encode($result ? ["success" => true, "message" => "Produk berhasil disimpan"] : ["message" => "Gagal menyimpan ke database"]);
            } else {
                echo json_encode(["message" => "Kesalahan pada statement database"]);
            }
        } else {
            echo json_encode(["message" => "Gagal memindahkan file yang diupload"]);
        }
    }
}

?>
<?php
function updateProduk(){
    global $conn;

    // Ambil data dari POST
    $idProduk = $_POST['id'];
    $namaProduk = $_POST['namaProduk'];
    $kodeProduk = $_POST['kodeProduk'];
    $jenisProduk = $_POST['jenisProduk'];
    $harga = $_POST['harga'];

    // Periksa apakah ada file gambar yang diupload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $uploadDir = "./AsetFoto/Catalog/";

        // Validasi dan simpan file baru
        if (!in_array($file['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
            exit(json_encode(["message" => "Invalid file type. Only JPG, PNG, and GIF files are allowed."]));
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file["name"]);
        $filePath = $uploadDir . $fileName;
        $counter = 1;
        while (file_exists($filePath)) {
            $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . "_{$counter}." . pathinfo($fileName, PATHINFO_EXTENSION);
            $counter++;
        }

        if (move_uploaded_file($file["tmp_name"], $filePath)) {
            $fileNameForDB = basename($filePath);
        } else {
            exit(json_encode(["message" => "Gagal memindahkan file yang diupload"]));
        }
    } else {
        // Jika tidak ada file baru, gunakan gambar lama
        $query = "SELECT gambar FROM produk WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $idProduk);
            $stmt->execute();
            $stmt->bind_result($fileNameForDB);
            $stmt->fetch();
            $stmt->close();
        } else {
            exit(json_encode(["message" => "Kesalahan pada statement database"]));
        }
    }

    // Update data produk
    $stmt = $conn->prepare("UPDATE produk SET kodeproduk = ?, nama = ?, gambar = ?, harga = ?, jenis = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("sssisi", $kodeProduk, $namaProduk, $fileNameForDB, $harga, $jenisProduk, $idProduk); // s: string, i: integer
        $result = $stmt->execute();
        $stmt->close();

        echo json_encode($result ? ["success" => true, "message" => "Produk berhasil diperbarui"] : ["message" => "Gagal memperbarui data di database"]);
    } else {
        echo json_encode(["message" => "Kesalahan pada statement database"]);
    }
}
?>






