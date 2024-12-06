<?php
require_once 'Database.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAPRO</title>
    <link rel="website icon" type="image/jpeg" href="AsetFoto/Login/rins_logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/tailwind.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js\SIMASTOK.js"></script>
</head>

<body>
    <!--navbar-->
    <nav class="bg-red-800">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <img class="h-8 w-auto" src="AsetFoto/Produk/LOGO_RINS.png" alt="Your Company">
                </div>
                <div class="hidden sm:block">
                    <div class="flex space-x-10">
                        <a href="#"
                            class="text-white underline underline-offset-8 px-3 py-2 rounded-md text-xl font-medium"
                            aria-current="page">Chart</a>
                        <a href="admin_catalog.php"
                            class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Catalog</a>
                        <a href="admin_image.php"
                            class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Image</a>
                        <a href="admin_history.php"
                            class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">History</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="home_customer.php">
                        <i class='bx bxs-user-circle text-4xl px-7 text-gray-300 hover:text-amber-300'></i>
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <!--chart-->

    <div class="bg-slate-300 w-[200vb] h-[100vb] p-5 pb-2 pt-2 rounded-lg shadow-lg mx-12 my-8 ml-20 ">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Penjualan</h2>
        <canvas id="myChart" class="w-full h-full "></canvas>
    </div>


    <!--rekap-->

    <div class="flex-auto top-24 flex justify-end space-x-4 mr-4">
        <div class="bg-gray-200 p-4 rounded-lg shadow-lg w-24 h-24 flex items-center justify-center"
            onclick="inputdata()">
            <i class="bx bx-plus text-3xl text-black hover:text-white"></i> <!-- Plus icon -->
        </div>
        <div class="flex flex-col justify-center space-y-3 pt-1 ">
            <button
                class="bg-gray-100 h-2/5  text-black px-3 py-2 rounded-md hover:bg-yellow-300 transition duration-200 "
                onclick="showdelinfo()"><i class='bx bx-trash'></i><span>Delete</span>
            </button>
        </div>
    </div>

    <!--promosi-->
    
<?php
// Query untuk mengambil semua data dari tabel saran
$result = $conn->query("SELECT * FROM saran");

?>
<div class="container mx-auto px-4 py-6">
<div class="inline-flex bg-white border-2 border-black rounded-lg shadow-lg pr-6 ">
            <h2 class="text-lg font-bold mb-1 ml-2">Promosi</h2>
        </div>
    <div class="bg-slate-100 rounded-lg shadow-lg p-4">
        <?php
        if ($result->num_rows > 0) {
            // Loop melalui setiap baris hasil query
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='p-4 mb-4 bg-white rounded-lg shadow-md border border-gray-200'>
                    <p class='text-sm font-semibold text-gray-700'><span class='font-bold'>Produk:</span> " . htmlspecialchars($row['nama']) . "</p>
                    <p class='text-sm text-gray-600'><span class='font-bold'>Saran:</span> " . htmlspecialchars($row['saran']) . "</p>
                    <p class='text-sm text-gray-600'><span class='font-bold'>Jumlah Terjual:</span> " . htmlspecialchars($row['jumlah_terjual']) . "</p>
                </div>";
            }
        } else {
            // Jika tidak ada data
            echo "<div class='p-4 text-gray-700 bg-white rounded-lg shadow-md'>Tidak ada promosi yang tersedia.</div>";
        }
        ?>
    </div>
</div>

</body>

</html>