<?php
require_once 'database.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAPRO</title>
    <link rel="website icon" type="image/jpeg" href="AsetFoto/Login/rins_logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/tailwind.css">
    <script src="js\SIMASTOK.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a href="admin_chart.php"
                            class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Chart</a>
                        <a href="admin_catalog.php"
                            class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Catalog</a>
                        <a href="#"
                            class="text-white underline underline-offset-8 px-3 py-2 rounded-md text-xl font-medium"
                            aria-current="page">Image</a>
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
    <!--navbar-->

    <!--action-->
    <?php
$sql = "SELECT id_promosi, gambar FROM promosi";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
?>
    <div class="flex justify-center items-center mt-16 ">
        <div class="bg-slate-100 rounded-lg p-8 max-w-xl w-full flex flex-row mx-auto items-center">
            <div class="flex justify-center items-center mb-4 mr-8">
                <img src="AsetFoto/carousel/<?php echo htmlspecialchars($row["gambar"]); ?>" alt="Deskripsi Gambar" class="object-fill">
            </div>
            <div class="flex space-x-4 mt-4">
                <button class="flex flex-col items-center text-black hover:text-red-500"
                    onclick="showdel(<?php echo $row['id_promosi']; ?>)">
                    <i class='bx bx-trash'></i>
                    <span>Delete Image</span>
                </button>
                <button class="flex flex-col items-center text-black hover:text-green-500"
                    data-id="<?php echo $row['id_promosi']; ?>" onclick="showadd(this)">
                    <i class="bx bx-image-add"></i>
                    <span>Upload Image</span>
                </button>
            </div>
        </div>
    </div>
<?php 
} 
?>

    <div class="flex justify-center items-center mt-16 ">
        <div
            class="bg-slate-100  rounded-lg p-8 max-w-xl w-full flex flex-row mx-auto items-center">
            <div class="flex justify-center items-center mb-4 mr-8" onclick="inputgambar()">
                <img src="AsetFoto\Produk\noimage.png" alt="Deskripsi Gambar" class="object-fill">
            </div>
            <div class="flex space-x-8 mt-4">
                <button class="flex flex-col items-center text-black hover:text-red-500">
                    <i class='bx bx-trash'></i>
                    <span>Delete Image</span>
                </button>
                <button class="flex flex-col items-center text-black hover:text-green-500">
                    <i class='bx bx-image-add'></i>
                    <span>Upload Image</span>
                </button>
            </div>
        </div>
    </div>

    <body>

</html>