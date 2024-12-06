<?php 
require '../Database.php';

$keyword = $_GET["keyword"];

$query = "SELECT * FROM produk 
                WHERE 
                kodeproduk LIKE '%$keyword%' OR
                nama LIKE '%$keyword%' OR
                jenis LIKE '%$keyword%' 
            ";
$produk = query($query);


?>
<!-- Upload Card -->
<?php if (count($produk) > 0): ?>
<button onclick="window.location.href='admin_catalog_input.php'">
            <div class="relative group">
                <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-400 bg-gray-100 rounded-lg h-[380px] cursor-pointer transition duration-300 hover:border-red-500 hover:bg-gray-50">
                    <div class="text-6xl mb-4">⬆️</div>
                    <p class="text-gray-500 text-center px-4">Click to upload new product</p>
                </div>
            </div>
            </button>
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
                                onclick="window.location.href='admin_catalog_change.php?id=<?= $row['id'] ?>'">
                            <span class="mr-2">✏️</span> Edit
                        </button>
                        <button class="flex-1 bg-red-600 text-white py-2 rounded-md hover:bg-red-700 transition duration-150 flex items-center justify-center"
                                onclick="deleteProduct(<?= $row['id'] ?>)">
                            <span class="mr-2">🗑️</span> Delete
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
    <div class="col-span-full text-center py-8">
        <p class="text-gray-600">No products found for "<strong><?= htmlspecialchars($keyword); ?></strong>".</p>
    </div>
<?php endif; ?>