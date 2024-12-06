<?php
require 'Database.php';
$produk = query("SELECT * FROM produk");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAPRO</title>
    <link rel="website icon" type="image/jpeg" href="AsetFoto/Login/rins_logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link rel="stylesheet" href="css/tailwind.css">
    <script src="js\SIMASTOK.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50">
    <!--navbar-->
    <nav class="bg-red-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <img class="h-8 w-auto" src="AsetFoto/Produk/LOGO_RINS.png" alt="Your Company">
                </div>
                <div class="hidden sm:block">
                    <div class="flex space-x-10">
                        <a href="admin_chart.php" class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium transition duration-150">Chart</a>
                        <a href="#" class="text-white underline underline-offset-8 px-3 py-2 rounded-md text-xl font-medium" aria-current="page">Catalog</a>
                        <a href="admin_image.php" class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium transition duration-150">Image</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="home_customer.php">
                        <i class='bx bxs-user-circle text-4xl px-7 text-gray-300 hover:text-amber-300 transition duration-150'></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <form action="" method="post" class="flex justify-center items-center space-x-2 mt-8">
    <!-- Input Field -->
    <input 
        type="text" 
        name="keyword" 
        placeholder="Search"
        id="keyword"
        class="w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
    >

    <!-- Search Button -->
    <button 
        type="submit" 
        name="cari" 
        id="tombol-cari"
        class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
    >
        Search
    </button>
</form>



    <main class="max-w-7xl mx-auto p-6">
        <div id ="container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Upload Card -->
            <button onclick="window.location.href='admin_catalog_input.php'">
            <div class="relative group">
                <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-400 bg-gray-100 rounded-lg h-[380px] cursor-pointer transition duration-300 hover:border-red-500 hover:bg-gray-50">
                    <div class="text-6xl mb-4">⬆️</div>
                    <p class="text-gray-500 text-center px-4">Click to upload new product</p>
                </div>
            </div>
            </button>

            <!-- Product Cards -->
            <?php foreach($produk as $row) : ?>
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                <!-- Product Code -->
                <div class="bg-red-800 text-white px-4 py-2">
                    <p class="text-sm font-semibold">Code: <?= $row["kodeproduk"] ?></p>
                </div>
                
                <!-- Product Image -->
                <div class="relative h-60">
                    <img src="AsetFoto/Catalog/<?= $row["gambar"] ?>"
                         class="w-full h-full">
                </div>

                <!-- Product Details -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= $row["nama"]; ?></h3>
                    <p class="text-gray-600 text-sm mb-2"><?= $row["jenis"]; ?></p>
                    <p class="text-red-600 font-bold">Rp <?= number_format($row["harga"], 0, ',', '.') ?></p>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-2 mt-4">
                        <button class="flex-1 bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition duration-150 flex items-center justify-center" 
                                onclick="window.location.href='admin_catalog_update.php?id=<?= $row['id'] ?>'">
                            <span class="mr-2">✏️</span> Edit
                        </button>
                        <button 
                        class="flex-1 bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition duration-150 flex items-center justify-center"
                        onclick="hapusproduk(<?= $row['id'] ?>)">
                        <span class="mr-2">🗑️</span> Delete
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>

</html>