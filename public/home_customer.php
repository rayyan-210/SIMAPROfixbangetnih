<?php
require_once 'Database.php';
$produk = query("SELECT * FROM produk");
$produk_berdasarkan_jenis = [];

foreach ($produk as $row) {
  $jenis = strtolower($row['jenis']);
  if (!isset($produk_berdasarkan_jenis[$jenis])) {
    $produk_berdasarkan_jenis[$jenis] = [];
  }
  $produk_berdasarkan_jenis[$jenis][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RIN'S Store</title>
  <link rel="website icon" type="image/jpeg" href="AsetFoto/Login/rins_logo.png">
  <link rel="stylesheet" href="css/tailwind.css">
  <script src="js\SIMASTOK.js" defer></script>
</head>

<body class="bg-gradient-to-b from-red-900 to-red-800 text-white font-sans scroll-smooth">
  <!--navbar-->
  <nav class="bg-red-700 p-4 flex justify-between items-center  top-0 z-50">
    <img src="./AsetFoto/Produk/LOGO_RINS.png" class="w-28 h-10" />
    <div class="header">
      <div class="flex space-x-10">
        <a href="#home" class="hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Home</a>
        <a href="#produk" class="hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">Produk</a>
        <a href="#about" class="hover:text-amber-300 px-3 py-2 rounded-md text-xl font-medium">About</a>
      </div>
    </div>
    <div>
      <button class="bg-gray-200 text-red-700 rounded-full p-2">
        <a href="login_admin.php">👤</a>
      </button>
    </div>
  </nav>

  <!-- Beranda - Carousel -->
  <?php
  // Query untuk mendapatkan gambar dari database jika upload bernilai true
  $sql = "SELECT id_promosi, gambar FROM promosi WHERE uploud = 1 ORDER BY id_promosi DESC";
  $result = mysqli_query($conn, $sql);

  $slides = [];
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $slides[] = [
        'id' => $row['id_promosi'],
        'image' => 'AsetFoto/carousel/' . htmlspecialchars($row['gambar'], ENT_QUOTES, 'UTF-8')
      ];
    }
  } else {
    // Handle case where there are no images
    $slides = [];
  }
  ?>

  <section id="home" class="w-full ">
    <div class="p-16">
      <div class="max-w-4xl mx-auto relative rounded-lg  bg-red-700 p-8">
        <div class="relative w-full">
          <!-- Carousel wrapper -->
          <div class="flex justify-center items-center mb-2 h-[450px] w-full">
            <?php foreach ($slides as $index => $slide): ?>
              <div class="slide" style="display: <?php echo $index === 0 ? 'block' : 'none'; ?>;">
                <img src="<?php echo $slide['image']; ?>"
                  class="flex w-full h-[400px] max-w-2xl object- rounded-lg items-center shadow-lg transition-transform duration-500 ease-in-out transform hover:scale-105"
                  alt="Promotional Image ID: <?php echo $slide['id']; ?>">
              </div>
            <?php endforeach; ?>
          </div>

        </div>
        <div id="activeSlide"></div>

        <!-- Slider controls -->
        <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer" onclick="prevSlide()">
          <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-600/30 hover:bg-amber-300">
            <svg class="w-4 h-4 text-black " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
            </svg>
            <span class="sr-only">Previous</span>
          </span>
        </button>
        <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer" onclick="nextSlide()">
          <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-600/30 hover:bg-amber-300">
            <svg class="w-4 h-4 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
            </svg>
            <span class="sr-only">Next</span>
          </span>
        </button>
      </div>
    </div>
  </section>
  <!-- Produk Section -->
  <section id="produk" class="py-16">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold capitalize text-gray-100 tracking-wide">
        RIN's
        <span
          class="relative px-6 py-2 bg-yellow-500 text-black font-semibold uppercase rounded-md shadow-lg transition-transform transform group duration-300 ease-in-out hover:scale-110">
          produk
          <span
            class="absolute inset-0 w-full h-full rounded-md bg-yellow-300 opacity-20 blur-md -z-10 transition duration-300 group-hover:opacity-50 group-hover:blur-lg">
          </span>
          <span
            class="absolute inset-0 border-4 border-yellow-500 rounded-md scale-95 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300">
          </span>
        </span>
      </h1>
    </div>

    <div class="max-w-6xl mx-auto gap-6">
    <?php foreach ($produk_berdasarkan_jenis as $jenis => $produk) : ?>
      <span
          class="relative px-6 py-2 bg-yellow-500 text-black font-semibold uppercase rounded-md shadow-lg transition-transform transform group duration-300 ease-in-out hover:scale-110">
          <?= htmlspecialchars($jenis); ?>
          <span
            class="absolute inset-0 w-full h-full rounded-md bg-yellow-300 opacity-20 blur-md -z-10 transition duration-300 group-hover:opacity-50 group-hover:blur-lg">
          </span>
          <span
            class="absolute inset-0 border-4 border-yellow-500 rounded-md scale-95 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300">
          </span>
        </span>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"> 
            <?php foreach ($produk as $row) : ?>
                <div class="bg-red-700 p-4 rounded-lg text-center mb-4"> 
                    <img src="AsetFoto/Catalog/<?= htmlspecialchars($row["gambar"]); ?>" alt="Product" class="w-full h-40 object-cover rounded-md" />
                    <h3 class="text-lg mt-2"><?= htmlspecialchars($row["nama"]); ?></h3>
                    <p class="text-yellow-400 font-bold">Rp <?= number_format($row["harga"], 0, ',', '.') ?></p>
                    <button class="bg-yellow-500 hover:scale-125 duration-300 scale-100 transition-all text-red-800 py-2 px-4 mt-2 rounded-full font-semibold"
                        onclick="sendToWhatsApp('<?= htmlspecialchars($row['id']); ?>', '<?= htmlspecialchars($row['nama']); ?>', <?= $row['harga']; ?>)">BUY</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

  </section>

  <!-- Footer -->
  <footer id="about" class="bg-black py-8 mt-8">
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold capitalize text-gray-100 tracking-wide">
        RIN's
        <span
          class="relative px-6 py-2 bg-yellow-500 text-black font-semibold uppercase rounded-md shadow-lg transition-transform transform group duration-300 ease-in-out hover:scale-110">
          ABOUT
          <span
            class="absolute inset-0 w-full h-full rounded-md bg-yellow-300 opacity-20 blur-md -z-10 transition duration-300 group-hover:opacity-50 group-hover:blur-lg">
          </span>
          <span
            class="absolute inset-0 border-4 border-yellow-500 rounded-md scale-95 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300">
          </span>
        </span>
      </h1>
    </div>




    <div class="max-w-4xl mx-auto flex justify-between items-center space-x-10">
      <div>
        <h3 class="text-lg font-semibold">Contact Details</h3>
        <ul>
          <li>0821-2525-2690</li>
          <li>Instagram: @rinsstore_frozenfood</li>
          <li>Email: rinstore@gmail.com</li>
        </ul>
      </div>
      <div>
        <h3 class="text-lg font-semibold">About Us</h3>
        <p>Rin's Frozen Food adalah sebuah UMKM yang menjual berbagai macam frozen food, seperti nugget, sosis, tempura, dan produk sejenis lainnya.</p>
      </div>
      <div>
        <!-- Link ke Google Maps -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.992198635895!2d112.59646567476842!3d-7.999740392026111!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7883576e5b831b%3A0xbbfda912f17e2ed1!2sRin&#39;s%20Frozen%20Food!5e0!3m2!1sen!2sid!4v1730651739721!5m2!1sen!2sid"
          width="240"
          height="180"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </footer>
</body>

</html>