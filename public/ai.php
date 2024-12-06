<?php
require_once 'Database.php';

// Mengambil data dari tabel penjualan
function runPromosi($conn){
$query = "SELECT * FROM penjualan";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Data penjualan
    $penjualan = [];
    while ($row = $result->fetch_assoc()) {
        $penjualan[] = $row;
    }
    
    // Menggabungkan data penjualan berdasarkan nama produk
    $penjualan_grouped = [];
    foreach ($penjualan as $item) {
        $nama = $item['nama'];
        $jumlah_terjual = intval($item['stok']);
    
        if (!isset($penjualan_grouped[$nama])) {
            $penjualan_grouped[$nama] = 0;
        }
        $penjualan_grouped[$nama] += $jumlah_terjual;
    }
    
    // Variasi rekomendasi untuk setiap kategori
    $rekomendasi_tinggi = [
        "Fokus promosi tambahan, seperti diskon kecil untuk pembelian dalam jumlah banyak.",
        "Buat kampanye loyalitas pelanggan untuk meningkatkan pembelian.",
        "Tingkatkan stok agar tidak kehabisan produk selama permintaan tinggi.",
        "Adakan program referral untuk pelanggan yang merekomendasikan produk.",
        "Buat konten media sosial dengan ulasan pelanggan untuk meningkatkan penjualan.",
        "Adakan promosi khusus, seperti cashback untuk pembelian kedua.",
        "Gunakan influencer lokal untuk mempromosikan produk.",
        "Tawarkan paket bundling dengan produk lain yang sering dibeli bersama.",
        "Adakan flash sale di platform e-commerce.",
        "Berikan promo gratis ongkir untuk pembelian jumlah tertentu."
    ];

    $rekomendasi_menurun = [
        "Tinjau ulang harga produk agar lebih kompetitif.",
        "Adakan survei untuk memahami alasan penurunan minat konsumen.",
        "Luncurkan promosi menarik seperti 'buy 1 get 1'.",
        "Gunakan iklan digital untuk menjangkau pelanggan baru.",
        "Tingkatkan kualitas pelayanan pelanggan untuk menarik minat.",
        "Tambahkan opsi pembayaran yang lebih fleksibel.",
        "Gunakan strategi diskon musiman untuk menarik pembeli.",
        "Kerjasama dengan mitra strategis untuk memperluas distribusi.",
        "Buat kampanye nostalgia untuk produk dengan sejarah panjang.",
        "Adakan demo produk gratis di lokasi ramai."
    ];

    $rekomendasi_stabil = [
        "Pertahankan strategi pemasaran saat ini.",
        "Gunakan media sosial untuk meningkatkan awareness.",
        "Cobalah kampanye diskon musiman untuk menarik pelanggan baru.",
        "Berikan potongan harga kecil sebagai penghargaan untuk pelanggan setia.",
        "Pasarkan produk sebagai pelengkap untuk pembelian lain.",
        "Tingkatkan komunikasi dengan pelanggan melalui email marketing.",
        "Gunakan data pelanggan untuk personalisasi promosi.",
        "Tambahkan testimoni pelanggan di materi pemasaran.",
        "Adakan giveaway kecil untuk menarik pelanggan baru.",
        "Buat ulasan produk di media sosial untuk meningkatkan kepercayaan."
    ];

    // Analisis dan pengelompokan
    $hasil_analisis = [
        "tinggi" => [],
        "menurun" => [],
        "stabil" => []
    ];

    foreach ($penjualan_grouped as $nama => $jumlah_terjual) {
        if ($jumlah_terjual > 100) {
            $hasil_analisis["tinggi"][] = ["nama" => $nama, "jumlah_terjual" => $jumlah_terjual];
        } elseif ($jumlah_terjual <60) {
            $hasil_analisis["menurun"][] = ["nama" => $nama, "jumlah_terjual" => $jumlah_terjual];
        } else {
            $hasil_analisis["stabil"][] = ["nama" => $nama, "jumlah_terjual" => $jumlah_terjual];
        }
    }

    // Menyimpan rekomendasi ke database
    foreach ($hasil_analisis as $kategori => $produk_list) {
        foreach ($produk_list as $produk) {
            $nama = $produk["nama"];
            $jumlah_terjual = $produk["jumlah_terjual"];
    
            // Pilih rekomendasi berdasarkan kategori
            if ($kategori == "tinggi") {
                $rekomendasi = array_rand(array_flip($rekomendasi_tinggi), 3);
            } elseif ($kategori == "menurun") {
                $rekomendasi = array_rand(array_flip($rekomendasi_menurun), 3);
            } else {
                $rekomendasi = array_rand(array_flip($rekomendasi_stabil), 3);
            }
    
            $saran_text = implode("\n", $rekomendasi);
    
            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO saran (nama, jumlah_terjual, saran) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $nama, $jumlah_terjual, $saran_text);
            if ($stmt->execute()) {
                echo "Rekomendasi untuk produk '$nama' berhasil disimpan.<br>";
            } else {
                echo "Error: " . $stmt->error . "<br>";
            }
        }
    }
    
    
    echo "Semua rekomendasi berhasil disimpan ke dalam tabel saran.<br>";
} else {
    echo "Data penjualan kosong.<br>";
}
foreach ($hasil_analisis as $kategori => $produk_list) {
    foreach ($produk_list as $produk) {
        // Debug data sebelum dimasukkan
        print_r($produk);
    }
}
}

?>
