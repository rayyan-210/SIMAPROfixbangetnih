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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/SIMASTOK.js" defer></script>
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
                        <a href="admin_chart.php" class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Chart</a>
                        <a href="#" class="text-white underline underline-offset-8 px-3 py-2 rounded-md text-xl font-medium" aria-current="page">Catalog</a>
                        <a href="admin_image.php" class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Image</a>
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

    <!-- Container -->

    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white shadow-xl border max-w-5xl border-black rounded-lg p-12">
            <!-- Image Upload Section -->
            <div class="flex flex-col md:flex-row items-start gap-8">
                <!-- Image Preview and Upload -->
                <div class="flex flex-col items-center">
                    <!-- Field Gambar -->
                    <div class="border-dashed border-2 border-gray-300 flex justify-center items-center overflow-hidden">
                        <input type="file" id="imageUpload" class="hidden" accept="image/*" onchange="SIMAPRO.previewImage(event)">
                        <img id="imagePreview" class="max-w-full max-h-96 object-contain" src="" alt="Preview" style="display:none;">
                    </div>
                    <!-- Tombol Upload Gambar -->
                    <button type="button" class="mt-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded" onclick="document.getElementById('imageUpload').click();">
                        <i class="bx bx-upload"></i> Upload Gambar
                    </button>
                </div>

                <!-- Form Fields -->
                <div class="flex-1 space-y-4">
                    <div class="w-72">
                        <label for="kodeProduk" class="block text-sm font-medium text-gray-700">Kode Produk</label>
                        <input type="text" id="kodeProduk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Kode Produk">
                    </div>
                    <div class="w-72">
                        <label for="namaProduk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" id="namaProduk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Nama Produk">
                    </div>
                    <div class="w-72">
                        <label for="jenisProduk" class="block text-sm font-medium text-gray-700">Jenis Produk</label>
                        <input type="text" id="jenisProduk" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" placeholder="Jenis Produk">
                    </div>
                    <div class="w-72">
                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">Rp.</span>
                            <input type="text" id="harga" class="block w-full px-3 py-2 border border-gray-300 rounded-r-md shadow-sm" placeholder="Harga">
                        </div>
                    </div>
                    <button type="button" class="bg-blue-500 text-white font-bold px-5 py-2 rounded-xl hover:bg-blue-600" onclick="SIMAPRO.submitForm()">Submit</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
