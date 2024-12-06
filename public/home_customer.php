<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RIN'S Store</title>
  <link rel="website icon" type="image/jpeg" href="AsetFoto/Login/rins_logo.png">
  <link rel="stylesheet" href="css/tailwind.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-red-800 text-white font-sans scroll-smooth">
  <!-- Navbar -->
  <nav class="bg-red-700 p-4 flex justify-between items-center  top-0 z-50">
    <img src="./AsetFoto/Produk/LOGO_RINS.png" class="w-28 h-10" />
    <div class="flex space-x-10">
      <a href="#home" class="hover:text-yellow-300">Home</a>
      <a href="#produk" class="hover:text-yellow-300">Produk</a>
      <a href="#about" class="hover:text-yellow-300">About</a>
    </div>
    <div>
      <button class="bg-gray-200 text-red-700 rounded-full p-2">
        <a href="login_admin.php">👤</a>
      </button>
    </div>
  </nav>

  <!-- Beranda - Carousel -->
  <section id="home" class="w-full bg-gray-900">
    <div class="p-16">
      <div
        class="max-w-4xl mx-auto relative"
        x-data="{
                activeSlide: 1,
                slides: [
                { id: 1, image: './AsetFoto/carousel/BARCA.png', title: 'Hello 1', body: 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.'},
                { id: 2, image: './AsetFoto/carousel/carousel 2.jpg', title: 'Hello 2', body: 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.'},
                { id: 3, image: './AsetFoto/carousel/carousel 3.jpg', title: 'Hello 3', body: 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.'},
                { id: 4, image: './AsetFoto/carousel/carousel 4.jpg', title: 'Hello 4', body: 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.'},
                { id: 5, image: './AsetFoto/carousel/carousel 5.jpg', title: 'Hello 5', body: 'Lorem ipsum dolor, sit amet consectetur adipisicing elit.'},
                ],
                loop(){
                    setInterval(() => {this.activeSlide = this.activeSlide === 5 ? 1 : this.activeSlide + 1}, 3000)
                }
            
            
            }"
        x-init="loop">
        <!---data loop -->
        <div class="relative w-full h-80 flex items-center justify-center bg-slate-500 text-white rounded-lg">
          <template x-for="slide in slides" :key="slide.id">
            <div x-show="activeSlide === slide.id" class="p-8 md:p-24 h-80 flex items-center bg-slate-500 text-white rounded-lg">
              <img :src="slide.image" alt="Slide Image" class="w-full max-h-96 object-cover rounded-lg" />
            </div>
          </template>

          <!-- Back/Next Buttons -->
          <div class="absolute inset-0 flex items-center justify-between px-4">
            <!-- Back Button -->
            <button
              x-on:click="activeSlide = activeSlide === 1 ? slides.length : activeSlide - 1"
              class="bg-slate-100 text-slate-500 hover:bg-blue-500 hover:text-white transition font-bold rounded-full w-12 h-12 shadow flex justify-center items-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
              </svg>
            </button>

            <!-- Next Button -->
            <button
              x-on:click="activeSlide = activeSlide === slides.length ? 1 : activeSlide + 1"
              class="bg-slate-100 text-slate-500 hover:bg-blue-500 hover:text-white transition font-bold rounded-full w-12 h-12 shadow flex justify-center items-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
              </svg>
            </button>
          </div>
        </div>


        <div class="absolute w-full flex items-center justify-center px-4">
          <template x-for="slide in slides" :key="slide.id">
            <button
              class="flex-1 w-4 h-2 mt-4 mx-2 mb-2 rounded-full overflow-hidden transition-colors duration-200 ease-out hover:bg-slate-600 hover:shadow-lg"
              :class="{
                'bg-blue-600' : activeSlide === slide.id,
                'bg-grey-300' : activeSlide !== slide.id,
              }"
              x-on:click="activeSlide = slide.id"></button>
          </template>
        </div>
      </div>
    </div>
  </section>

  <!-- Produk Section -->
  <section id="produk" class="py-8 bg-red-900">
    <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div class="bg-red-700 p-4 rounded-lg text-center">
        <img src="./AsetFoto/Produk/Produk 1 kanzler crispy chicken nugget.jpg" alt="Product" class="w-full h-40 object-cover rounded-md" />
        <h2 class="text-lg mt-2">KANZLER NUGET SPICY</h2>
        <p class="text-yellow-400 font-bold">Rp. 43.500</p>
        <button class="bg-yellow-500 text-red-800 py-2 px-4 mt-2 rounded-full font-semibold">BUY</button>
      </div>
      <div class="bg-red-700 p-4 rounded-lg text-center">
        <img src="./AsetFoto/Produk/Produk 1 kanzler crispy chicken nugget.jpg" alt="Product" class="w-full h-40 object-cover rounded-md" />
        <h2 class="text-lg mt-2">KANZLER NUGET SPICY</h2>
        <p class="text-yellow-400 font-bold">Rp. 43.500</p>
        <button class="bg-yellow-500 text-red-800 py-2 px-4 mt-2 rounded-full font-semibold">BUY</button>
      </div>
      <div class="bg-red-700 p-4 rounded-lg text-center">
        <img src="./AsetFoto/Produk/Produk 1 kanzler crispy chicken nugget.jpg" alt="Product" class="w-full h-40 object-cover rounded-md" />
        <h2 class="text-lg mt-2">KANZLER NUGET SPICY</h2>
        <p class="text-yellow-400 font-bold">Rp. 43.500</p>
        <button class="bg-yellow-500 text-red-800 py-2 px-4 mt-2 rounded-full font-semibold">BUY</button>
      </div>
      <div class="bg-red-700 p-4 rounded-lg text-center">
        <img src="./AsetFoto/Produk/Produk 1 kanzler crispy chicken nugget.jpg" alt="Product" class="w-full h-40 object-cover rounded-md" />
        <h2 class="text-lg mt-2">KANZLER NUGET SPICY</h2>
        <p class="text-yellow-400 font-bold">Rp. 43.500</p>
        <button class="bg-yellow-500 text-red-800 py-2 px-4 mt-2 rounded-full font-semibold">BUY</button>
      </div>
      <div class="bg-red-700 p-4 rounded-lg text-center">
        <img src="./AsetFoto/Produk/Produk 1 kanzler crispy chicken nugget.jpg" alt="Product" class="w-full h-40 object-cover rounded-md" />
        <h2 class="text-lg mt-2">KANZLER NUGET SPICY</h2>
        <p class="text-yellow-400 font-bold">Rp. 43.500</p>
        <button class="bg-yellow-500 text-red-800 py-2 px-4 mt-2 rounded-full font-semibold">BUY</button>
      </div>
      <div class="bg-red-700 p-4 rounded-lg text-center">
        <img src="./AsetFoto/Produk/Produk 1 kanzler crispy chicken nugget.jpg" alt="Product" class="w-full h-40 object-cover rounded-md" />
        <h2 class="text-lg mt-2">KANZLER NUGET SPICY</h2>
        <p class="text-yellow-400 font-bold">Rp. 43.500</p>
        <button class="bg-yellow-500 text-red-800 py-2 px-4 mt-2 rounded-full font-semibold">BUY</button>
      </div>
      <!-- Duplikat elemen ini untuk produk lainnya -->
    </div>
  </section>

  <!-- Footer -->
  <footer id="about" class="bg-black py-8 mt-8">
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