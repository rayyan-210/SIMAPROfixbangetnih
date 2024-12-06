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

//alert tambah
function showadd(isButtonA) {
  Swal.fire({
    title: "TAMBAH?",
    text: "Data akan ditambahkan",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, tambah aja!",
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "SUCCESS!",
        text: "BERHASIL!",
        icon: "success",
      }).then(() => {
        if (isButtonA) {
          window.location.href = "admin_catalog.php";
        }
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
    confirmButtonText: "Ya, hapus saja!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Lakukan AJAX untuk menghapus data
      fetch(`admin_image_hapus.php?id=${id}`, {
        method: "GET",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            Swal.fire({
              title: "Deleted!",
              text: "Berhasil dihapus!",
              icon: "success",
            });
          } else {
            Swal.fire("Gagal", data.message || "Gagal menghapus gambar", "error");
          }
        })
        .then((data) => {
          Swal.close(); // Close loading
          Swal.fire("success", "Berhasil menghapus gambar dari database", "success").then(() => {
            setTimeout(() => {
              location.reload();
            }, 700);
          });
        })
        .catch((error) => {
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
        confirmButtonText: "Save",
        cancelButtonText: "Cancel",
      });

      if (result.isConfirmed) {
        let formData = new FormData();
        formData.append("file", file);
        formData.append("uploadType", "image");

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
            Swal.fire("success", "gambar berhasil masuk kedalam database", "success").then(() => {
              setTimeout(() => {
                location.reload();
              }, 700);
            });
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }
    };

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
    icon: "warning",
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: "Delete and Save",
    denyButtonText: `Delete and Don't save`,
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire("Saved!", "", "success");
    } else if (result.isDenied) {
      fetch("Database.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "aksi=hapus_semua",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            Swal.fire("Success", "Data telah terhapus!", "success").then(() => {
              setTimeout(() => location.reload(), 700);
            });
          } else {
            Swal.fire({
              title: "Gagal!",
              text: data.pesan,
              icon: "error",
            });
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan saat menghapus data",
            icon: "error",
          });
        });
    }
  });
}

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

    fetch("admin_catalog_input.php", {
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

const SIMAPRO_update = (() => {
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
    const idProduk = document.getElementById("idProduk").value;

    if (!kodeProduk || !namaProduk || !jenisProduk || !harga) {
      Swal.fire("Error", "Semua kolom harus diisi", "error");
      return;
    }

    let formData = new FormData();
    formData.append("id", idProduk);
    formData.append("kodeProduk", kodeProduk);
    formData.append("namaProduk", namaProduk);
    formData.append("jenisProduk", jenisProduk);
    formData.append("harga", harga);
    if (file) {
      formData.append("file", file);
    } else {
      formData.append("file", "null"); // Menandai bahwa gambar tidak diupdate
    }

    fetch("admin_catalog_update.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          Swal.fire("Success", "Produk berhasil diperbarui", "success").then(() => {
            window.location.href = "admin_catalog.php";
          });
        } else {
          Swal.fire("Error", data.message, "error");
        }
      })
      .catch((error) => {
        Swal.fire("Error", "Terjadi kesalahan saat memperbarui produk", "error");
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
      fetch(`admin_catalog_hapus.php?id=${id}`, {
        method: "GET",
      })
        .then((response) => response.json()) // Konversi respons ke JSON
        .then((data) => {
          if (data.success) {
            // Jika sukses
            Swal.fire({
              title: "Terhapus!",
              text: data.message,
              icon: "success",
            }).then(() => {
              // Hilangkan elemen produk dari halaman
              var productElement = document.getElementById("product-" + id);
              if (productElement) {
                productElement.remove();
              }
              // Redirect jika ingin kembali ke halaman tertentu
              window.location.href = "admin_catalog.php";
            });
          } else {
            // Jika gagal
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
