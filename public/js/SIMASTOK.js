//chart
// Script memperbarui labels dan datasets dari respon Database.php
document.addEventListener("DOMContentLoaded", async function () {
  const ctx = document.getElementById("myChart").getContext("2d");

  try {
    const response = await fetch("Database.php?json=true");
    if (!response.ok) throw new Error("Network response was not ok");

    const Database = await response.json();
    console.log("Database Data:", Database); // Debug: Lihat data dari Database.php

    if (!Array.isArray(Database)) {
      throw new Error("Data is not in expected format");
    }

    // Mengelompokkan data berdasarkan tanggal dan nama produk
    const groupedData = {};

    Database.forEach((item) => {
      const date = item.tanggal;
      const productName = item.nama.toLowerCase().trim();
      const stock = Number(item.stok); // Pastikan stok adalah angka dengan konversi

      // Jika tanggal belum ada di objek, buat entri baru
      if (!groupedData[date]) {
        groupedData[date] = {};
      }

      // Jika produk belum ada di tanggal tersebut, inisialisasi dengan stok
      if (!groupedData[date][productName]) {
        groupedData[date][productName] = 0;
      }

      // Tambahkan stok ke produk pada tanggal tersebut
      groupedData[date][productName] += stock; // Menjumlahkan stok
    });

    // Mengambil label dan datasets
    const labels = Object.keys(groupedData);
    const products = [...new Set(Database.map((item) => item.nama.toLowerCase().trim()))]; // Mengambil daftar unik produk

    const datasets = products.map((product) => {
      return {
        label: `Penjualan ${product.charAt(0).toUpperCase() + product.slice(1)}`, // Menyusun label produk
        data: labels.map((date) => groupedData[date][product] || 0), // Ambil stok atau 0 jika tidak ada
        borderColor: getRandomColor(), // Fungsi untuk mendapatkan warna acak
        borderWidth: 2,
        fill: false,
      };
    });

    console.log("Labels:", labels);
    console.log("Datasets:", datasets);

    new Chart(ctx, {
      type: "line",
      data: {
        labels: labels,
        datasets: datasets,
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  } catch (error) {
    console.error("Error fetching data:", error);
    alert("Terjadi kesalahan dalam pengambilan data, silakan cek konsol untuk detail.");
  }
});

// Fungsi untuk menghasilkan warna acak
function getRandomColor() {
  const letters = "0123456789ABCDEF";
  let color = "#";
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

// alert tambah
function showadd(element) {
    const id_promosi = element.getAttribute('data-id'); // Ambil ID dari atribut data

    Swal.fire({
        title: 'Update Upload Status',
        text: 'Apakah Anda ingin upload foto ini ke homepage?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim permintaan untuk mengupdate status upload
            fetch('Database.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id_promosi }) // Gunakan ID yang diambil dari tombol
            })
                .then(response => response.json())
                .then(data => {
                    // Tampilkan pesan yang relevan kepada pengguna
                    if (data.success) {
                        Swal.fire({
                            title: 'SUCCESS!',
                            text: 'Status upload berhasil diupdate!',
                            icon: 'success'
                        }).then(() => {
                            // Reload halaman setelah sukses
                            setTimeout(() => {
                                location.reload();
                            }, 700);
                        });
                    } else {
                        Swal.fire({
                            title: 'ERROR!',
                            text: 'Gagal mengupdate status upload.',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                      title: 'SUCCESS!',
                      text: 'Status upload berhasil diupdate!',
                      icon: 'success'
                  }).then(() => {
                            // Reload halaman setelah sukses
                            setTimeout(() => {
                                location.reload();
                            }, 700);
                        });
                });
        } else {
            console.log("Pengguna membatalkan");
        }
    });
}

//alert hapus
function showdel(id) {
    Swal.fire({
        title: "HAPUS?",
        text: "Yakin ingin dihapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus saja!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Lakukan AJAX untuk menghapus data
            fetch(`Database.php?id=${id}`, {
                method: 'GET'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Berhasil dihapus!",
                            icon: "success"
                        })
                    } else {
                        Swal.fire("Gagal", data.message || "Gagal menghapus gambar", "error");
                    }
                }).then(data => {
                    Swal.close(); // Close loading
                    Swal.fire("success", "Berhasil menghapus gambar dari database", "success").then(() => {

                        setTimeout(() => {
                            location.reload();
                        }, 700);
                    });
                })
                .catch(error => {
                    console.error("Error:", error);

                });
        }
    });
}

// alert input gambar
async function inputgambar() {
  const { value: file } = await Swal.fire({
    title: "Select image",
    input: "file",
    inputAttributes: {
      accept: "image/*",
      "aria-label": "Upload your profile picture",
    },
  });

    if (file) {
        const reader = new FileReader();
        reader.onload = async (e) => {
            const result = await Swal.fire({
                title: "Your uploaded picture",
                imageUrl: e.target.result,
                imageAlt: "The uploaded picture",
                showCancelButton: true,
                confirmButtonText: 'Save',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append("file", file);
                formData.append("uploadType", "image");

                fetch("Database.php", {
                    method: "POST",
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);

                        }
                        return response.json();
                    })
                    .then(data => {
                        Swal.close(); // Close loading
                        Swal.fire("success", "gambar berhasil masuk kedalam database", "success").then(() => {
                            setTimeout(() => {
                                location.reload();
                            }, 700);
                        });
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            }
            return response.json();
          }

    reader.readAsDataURL(file);
  } else {
    Swal.fire("error", "No file selected", "error");
  }
}

// alert input data
async function inputdata() {
  const { value: file } = await Swal.fire({
    title: "Select Excel file",
    input: "file",
    inputAttributes: {
      accept: ".csv",
      "aria-label": "Upload your Excel file",
    },
  });

  if (file) {
    const result = await Swal.fire({
      title: "Your uploaded file",
      text: file.name,
      showCancelButton: true,
      confirmButtonText: "Save",
      cancelButtonText: "Cancel",
    });

    if (result.isConfirmed) {
      let formData = new FormData();
      formData.append("file", file);
      formData.append("uploadType", "csv");

      fetch("Database.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok: " + response.statusText);
          }
          return response.json();
        })
        .then((data) => {
          Swal.close(); // Close loading
          Swal.fire("Success", "File berhasil masuk ke dalam database", "success").then(() => {
            setTimeout(() => location.reload(), 700);
          });
        })
        .catch((error) => {
          console.error("Error:", error);
          Swal.fire("success", "file berhasil masuk kedalam database", "success").then(() => {
            setTimeout(() => {
              location.reload();
            }, 700);
          });
        });
    }
  } else {
    Swal.fire("Error", "No file selected", "error");
  }
}


// alert hapus dan save
function showdelinfo() {
  Swal.fire({
    title: "DELETE?",
    text: "Apakah ingin menyimpan data ke dalam history?",
    icon: 'warning',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: "Delete and Save",
    denyButtonText: `Delete and Don't save`
}).then((result) => {
    if (result.isConfirmed) {
        // Jika pengguna mengkonfirmasi "Delete and Save"
        fetch('Database.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'aksi=simpan_ke_history'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Jika penyimpanan ke history berhasil, hapus data dari tabel penjualan
                return fetch('Database.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'aksi=hapus_semua'
                });
            } else {
                Swal.fire({
                    title: "Gagal!",
                    text: data.pesan,
                    icon: "error"
                });
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.status === 'success') {
                Swal.fire("Success", "Data telah disimpan ke history dan dihapus!", "success").then(() => {
                    setTimeout(() => location.reload(), 700);
                });
            } else {
                Swal.fire({
                    title: "Gagal!",
                    text: data.pesan,
                    icon: "error"
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: "Error!",
                text: "Terjadi kesalahan saat menghapus data",
                icon: "error"
            });
        });
        
    } else if (result.isDenied) {
        fetch('Database.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'aksi=hapus_semua'
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire("Success", "Data telah terhapus!", "success").then(() => {
                        setTimeout(() => location.reload(), 700);
                    });
                } else {
                    Swal.fire({
                        title: "Gagal!",
                        text: data.pesan,
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: "Error!",
                    text: "Terjadi kesalahan saat menghapus data",
                    icon: "error"
                });
            });
    }
});
}

// slide homepage
(function() {
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slide');
  const totalSlides = slides.length;

  function showSlide(index) {
      slides.forEach((slide, i) => {
          slide.style.display = (i === index) ? 'block' : 'none';
      });
      
  }

  function nextSlide() {
      currentSlide = (currentSlide + 1) % totalSlides;
      showSlide(currentSlide);
  }

  function prevSlide() {
      currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
      showSlide(currentSlide);
  }



  // Expose nextSlide and prevSlide to the window
  window.nextSlide = nextSlide;
  window.prevSlide = prevSlide;
})();



const SIMAPRO = (() => {
  // Fungsi untuk menampilkan pratinjau gambar sebelum di-upload
  const previewImage = (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        const previewElement = document.getElementById("imagePreview");
        previewElement.src = reader.result;
        previewElement.style.display = "block"; // Menampilkan gambar
      };
      reader.readAsDataURL(file);
    }
  };

  // Fungsi untuk submit form dan upload data ke server
  const submitForm = () => {
    const kodeProduk = document.getElementById("kodeProduk").value;
    const namaProduk = document.getElementById("namaProduk").value;
    const jenisProduk = document.getElementById("jenisProduk").value;
    const harga = document.getElementById("harga").value;
    const fileInput = document.getElementById("imageUpload");
    const file = fileInput.files[0];

    if (!kodeProduk || !namaProduk || !jenisProduk || !harga || !file) {
      Swal.fire("Error", "Semua kolom harus diisi", "error");
      return;
    }

    let formData = new FormData();
    formData.append("kodeProduk", kodeProduk);
    formData.append("file", file);
    formData.append("namaProduk", namaProduk);
    formData.append("jenisProduk", jenisProduk);
    formData.append("harga", harga);

    fetch("Database.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          Swal.fire("Success", "Produk berhasil disimpan", "success").then(() => {
            window.location.href = "admin_catalog.php";
          });
        } else {
          Swal.fire("Error", data.message, "error");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        Swal.fire("Error", "Terjadi kesalahan saat menyimpan produk", "error");
      });
  };
  return {
    previewImage,
    submitForm,
  };
})();

function hapusproduk(id) {
  Swal.fire({
    title: "HAPUS?",
    text: "Yakin ingin dihapus!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, hapus saja!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Lakukan AJAX untuk menghapus data menggunakan fetch API
      fetch(`hapus_produk.php?id=${id}`, {
        // Mengarah ke file utama
        method: "GET",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire({
              title: "Deleted!",
              text: "Berhasil dihapus!",
              icon: "success",
            }).then(() => {
              // Setelah berhasil dihapus, hilangkan elemen produk dari halaman
              var productElement = document.getElementById("product-" + id);
              if (productElement) {
                productElement.remove(); // Menghapus elemen produk dari DOM
              }
            });
          } else {
            Swal.fire("Gagal", data.message || "Gagal menghapus produk", "error");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          Swal.fire("Gagal", "Terjadi kesalahan, silakan coba lagi.", "error");
        });
    }
  });
}

const SIMAPRO_update = {
  previewImage: function (event) {
    const reader = new FileReader();
    reader.onload = function () {
      const output = document.getElementById("imagePreview");
      output.src = reader.result;
      output.style.display = "block";
    };
    reader.readAsDataURL(event.target.files[0]);
  },

  submitForm: function () {
    const formData = new FormData();
    formData.append("id", '<?= $produk["id"] ?>'); // Ambil ID produk yang ingin diperbarui
    formData.append("kodeProduk", document.getElementById("kodeProduk").value);
    formData.append("namaProduk", document.getElementById("namaProduk").value);
    formData.append("jenisProduk", document.getElementById("jenisProduk").value);
    formData.append("harga", document.getElementById("harga").value);
    formData.append("oldImage", '<?= $produk["gambar"] ?>'); // Gambar lama
    const imageUpload = document.getElementById("imageUpload");
    if (imageUpload.files[0]) {
      formData.append("imageUpload", imageUpload.files[0]); // Gambar baru jika ada
    }

    // Kirim data ke backend menggunakan AJAX
    fetch("update_produk.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          Swal.fire({
            title: "Sukses!",
            text: data.message,
            icon: "success",
            confirmButtonText: "OK",
          }).then(() => {
            window.location.href = "admin_catalog.php"; // Redirect setelah sukses
          });
        } else {
          Swal.fire({
            title: "Error!",
            text: data.message,
            icon: "error",
            confirmButtonText: "OK",
          });
        }
      })
      .catch((error) => {
        Swal.fire({
          title: "Error!",
          text: "Terjadi kesalahan jaringan.",
          icon: "error",
          confirmButtonText: "OK",
        });
      });
  },
};

function sendToWhatsApp(id, nama, harga) {
  // Nomor WhatsApp admin
  const adminPhone = "6281221529676"; 
  
  // Pesan WhatsApp
  const message = `Halo, saya ingin membeli produk berikut ${nama}\n dengan Harga: Rp ${harga.toLocaleString('id-ID')}\n\nTerima kasih.`;

  // Buat tautan WhatsApp
  const waLink = `https://wa.me/${adminPhone}?text=${encodeURIComponent(message)}`;

  // Arahkan pengguna ke tautan WhatsApp
  window.open(waLink, '_blank');
}
