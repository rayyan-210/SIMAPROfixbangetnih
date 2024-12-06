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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js\SIMASTOK.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-red-800">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <img class="h-8 w-auto" src="AsetFoto/Produk/LOGO_RINS.png" alt="Your Company">
                </div>
                <div class="hidden sm:block">
                    <div class="flex space-x-10">
                        <a href="admin_chart.php" class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Chart</a>
                        <a href="admin_catalog.php" class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Catalog</a>
                        <a href="admin_image.php" class="text-gray-300 hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Image</a>
                        <a href="#" class="text-white underline underline-offset-8 px-3 py-2 rounded-md text-xl font-medium" aria-current="page">History</a>
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
    <!-- Table -->
    <form action="" method="post">
    <button type="submit" name="truncate">hapus</button></form>
    <div class="flex justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-3xl p-4">
            <table class="w-full text-center border-collapse border border-gray-400 bg-gray-200">
                <?php
                $query = "SELECT * FROM history"; // Ganti dengan nama tabel yang sesuai
                $result = $conn->query($query);
                ?>

                <table>
                    <thead>
                        <tr class="bg-gray-300">
                            <th class="border border-gray-400 p-2">NO</th>
                            <th class="border border-gray-400 p-2">Rentan Tanggal</th>
                            <th class="border border-gray-400 p-2">Tanggal Rekap</th>
                            <th class="border border-gray-400 p-2">Produk</th>
                            <th class="border border-gray-400 p-2">Jumlah</th>
                            <th class="border border-gray-400 p-2">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            $previous_date = ""; 
                            $previous_rentan ="";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='bg-gray-100'>";
                                if ($row['Rentan_Tanggal'] != $previous_rentan) {
                                    echo "<td class='border border-gray-400 p-2'>" . $no . "</td>";
                                    echo "<td class='border border-gray-400 p-2'>" . $row['Rentan_Tanggal'] . "</td>";
                                    $previous_rentan = $row['Rentan_Tanggal']; 
                                    $no++;
                                } else {
                                    echo "<td class='border border-gray-400 p-2'></td>"; 
                                    echo "<td class='border border-gray-400 p-2'></td>"; 
                                }
                                if ($row['Tanggal_rekap'] != $previous_date) {
                                    echo "<td class='border border-gray-400 p-2'>" . $row['Tanggal_rekap'] . "</td>";
                                    $previous_date = $row['Tanggal_rekap']; 
                                } else {
                                    echo "<td class='border border-gray-400 p-2'></td>";
                                }
                                echo "<td class='border border-gray-400 p-2'>" . $row['nama'] . "</td>";
                                echo "<td class='border border-gray-400 p-2'>" . $row['jumlah'] . "</td>";
                                echo "<td class='border border-gray-400 p-2'>". " Rp " . number_format($row["Total"], 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='border border-gray-400 p-2'>Tidak ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </table>
        </div>
    </div> 