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
            $uploadDir = "C:/Users/ASUS/OneDrive/Desktop/coding/HTML/SIMAPRO/public/AsetFoto/carousel/";
            $response = [];

            // Cek apakah ada error saat upload
            if ($file['error'] !== UPLOAD_ERR_OK) {
                exit(json_encode(["message" => "File upload error code: " . $file['error']]));
            }

            // Validasi tipe file
            if (!in_array($file['type'], ['image/jpeg', 'image/png', 'image/gif'])) {
                exit(json_encode(["message" => "Invalid file type. Only JPG, PNG, and GIF files are allowed."]));
            }

            // Buat direktori jika belum ada
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Tentukan nama file yang unik
            $fileName = basename($file["name"]);
            $filePath = $uploadDir . $fileName;
            $counter = 1;

            // Cek apakah file sudah ada, jika ya, tambahkan counter
            while (file_exists($filePath)) {
                $filePath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . "_{$counter}." . pathinfo($fileName, PATHINFO_EXTENSION);
                $counter++;
            }

            // Simpan file ke direktori
            if (move_uploaded_file($file["tmp_name"], $filePath)) {
                // Simpan informasi gambar ke database
                $stmt = $conn->prepare("INSERT INTO promosi (gambar, tanggal, uploud) VALUES (?, NOW(), 0)");
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
//  hapus gambar 
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET["id"];
    $uploadDir = "C:/Users/ASUS/OneDrive/Desktop/coding/HTML/SIMAPRO/public/AsetFoto/carousel/";

    $stmt_select = $conn->prepare("SELECT gambar FROM promosi WHERE id_promosi = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($row = $result->fetch_assoc()) {
        $fileName = $row['gambar'];
        $stmt_delete = $conn->prepare("DELETE FROM promosi WHERE id_promosi = ?");
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            echo json_encode(["success" => true, "message" => "Gambar berhasil dihapus dari database. File tetap ada di server."]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menghapus gambar dari database."]);
        }

        $stmt_delete->close();
    }

    $stmt_select->close();
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
                $expected_header = ['tanggal', 'kodeproduk', 'stok'];
                if ($header !== $expected_header) {
                    die(json_encode(["status" => "error", "message" => "Header CSV tidak sesuai dengan kolom tabel."]));
                }

                // Membaca data CSV baris demi baris
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $data = array_map('trim', $data);
                    if (count($data) === 3) {
                        $tanggal = $data[0];
                        $kodeproduk = $data[1];
                        $stok = $data[2];

                        // Ambil nama produk berdasarkan $kodeproduk
                        $query = "SELECT nama FROM produk WHERE kodeproduk = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("s", $kodeproduk);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $nama_produk = $row['nama'];

                            // Insert ke tabel penjualan
                            $insert_sql = "INSERT INTO penjualan (tanggal, kodeproduk,nama, stok) VALUES (?,?, ?, ?)";
                            $insert_stmt = $conn->prepare($insert_sql);
                            $insert_stmt->bind_param("sssi", $tanggal, $kodeproduk, $nama_produk, $stok);

                            if (!$insert_stmt->execute()) {
                                die(json_encode(["status" => "error", "message" => "Error menyisipkan data: " . $conn->error]));
                            }
                        } else {
                            die(json_encode(["status" => "error", "message" => "Codename tidak ditemukan: " . $kodeproduk]));
                        }
                    } else {
                        die(json_encode(["status" => "error", "message" => "Data tidak lengkap: " . implode(", ", $data)]));
                    }
                }

                fclose($handle);
                // Panggil fungsi AI setelah selesai upload data
                require_once 'ai.php'; // Pastikan file ini benar
                runPromosi($conn);

                echo json_encode(["status" => "success", "message" => "File berhasil dimasukkan ke dalam database dan rekomendasi telah dihasilkan."]);
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
// ketika save dan delete di tekan
if (isset($_POST['aksi']) && $_POST['aksi'] == 'simpan_ke_history') {
    // Ambil rentang tanggal dari tabel penjualan
    $queryTanggal = "SELECT MIN(tanggal) AS tanggal_pertama, MAX(tanggal) AS tanggal_terakhir FROM penjualan";
    $resultTanggal = mysqli_query($conn, $queryTanggal);
    $tanggalRow = mysqli_fetch_assoc($resultTanggal);
    $tanggal_pertama = $tanggalRow['tanggal_pertama'];
    $tanggal_terakhir = $tanggalRow['tanggal_terakhir'];
    $rentan_tanggal = "$tanggal_pertama s/d $tanggal_terakhir";

    // Ambil semua data dari tabel penjualan
    $query = "SELECT nama, SUM(stok) AS total_stok FROM penjualan GROUP BY nama";
    $result = mysqli_query($conn, $query);

    $tanggal_rekap = date('Y-m-d');

    while ($row = mysqli_fetch_assoc($result)) {
        $nama_produk = $row['nama'];
        $total_stok = $row['total_stok'];

        // Ambil harga produk berdasarkan nama
        $query_harga = "SELECT harga FROM produk WHERE nama = ?";
        $stmt = $conn->prepare($query_harga);
        $stmt->bind_param("s", $nama_produk);
        $stmt->execute();
        $result_harga = $stmt->get_result();

        $harga = 0;
        if ($row_harga = $result_harga->fetch_assoc()) {
            $harga = $row_harga['harga']; // Harga produk
        }
        $stmt->close();

        $total_harga = $total_stok * $harga; // Hitung total harga

        // Simpan ke tabel history
        $insertQuery = "INSERT INTO history (tanggal_rekap, rentan_tanggal, nama, jumlah, total) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssii", $tanggal_rekap, $rentan_tanggal, $nama_produk, $total_stok, $total_harga);
        $stmt->execute();
        $stmt->close();
    }

    // Mengosongkan tabel penjualan setelah menyimpan ke history
    $truncateQuery = "TRUNCATE TABLE penjualan";
    $truncateQuery2= "TRUNCATE TABLE saran";
    if (mysqli_query($conn, $truncateQuery) &&  mysqli_query($conn, $truncateQuery2)) {
        echo json_encode([
            'status' => 'success',
            'pesan' => 'Semua data berhasil disimpan ke history dan dihapus dari tabel penjualan.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'pesan' => 'Gagal menghapus data dari tabel penjualan atau tabel lain.'
        ]);
    exit();
}
}


?>

<?php
// menghapus data dalam tabel penjualan dan ga di save
if (isset($_POST['aksi']) && $_POST['aksi'] == 'hapus_semua') {

    $query1 = "DELETE FROM penjualan";
    $query2 = "DELETE FROM saran";

    if (mysqli_query($conn, $query1) && mysqli_query($conn, $query2)) {
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

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}




function hapus($id)
{
    global $conn;
    $query = "DELETE FROM produk WHERE id = '$id'";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}



// Fungsi untuk menangani upload gambar dan data produk
function uploadProduk()
{
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

            $stmt = $conn->prepare("INSERT INTO produk ( kodeproduk, nama, gambar, harga, jenis) VALUES ( ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sssis", $kodeProduk, $namaProduk, $fileNameForDB, $harga, $jenisProduk); // s: string, i: integer
                $result = $stmt->execute();
                $stmt->close();

                echo json_encode($result ? ["success" => true, "message" => "Produk berhasil disimpan"] : ["message" => "Gagal menyimpan ke database"]);
            } else {
                echo json_encode(["message" => "Kesalahan pada statement database"]);
            }
        }
    }
}

// Panggil fungsi upload
uploadProduk();
?>

<?php

function deleteProductById($id)
{
    global $conn;
    // Pastikan ID valid
    $id = intval($id);
    if ($id <= 0) {
        return ['status' => 'error', 'message' => 'Invalid ID'];
    }

    // Query untuk menghapus produk
    $query = "DELETE FROM produk WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return ['status' => 'success', 'message' => 'Product deleted successfully'];
    } else {
        $stmt->close();
        $conn->close();
        return ['status' => 'error', 'message' => 'Failed to delete product'];
    }
}


?>

<?php
// Mengubah boolean di gambar
$data = json_decode(file_get_contents("php://input"), true);
$id_promosi = $data['id'] ?? null;

$sql = "UPDATE promosi SET uploud = 1 WHERE id_promosi = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_promosi);

if ($stmt->execute()) {
    // Menangkap output dan menahannya
    ob_start();
    echo json_encode(["success" => true]);
    ob_end_clean();
} else {
    // Menangkap output dan menahannya
    ob_start();
    echo json_encode(["success" => false, "error" => $conn->error]);
    ob_end_clean();
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['truncate'])) {
    // Perintah TRUNCATE
    $truncateQuery = "TRUNCATE TABLE history";

    mysqli_query($conn, $truncateQuery);
}
?>