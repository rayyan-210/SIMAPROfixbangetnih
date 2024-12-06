<?php 
require 'Database.php';
if(isset($_POST["login"])){

  $username = $_POST["username"];
  $password = $_POST["password"];

  $result = mysqli_query($conn,"SELECT * FROM admin WHERE
        username = '$username'");

    //cek username
    if(mysqli_num_rows($result) === 1){
      //cek password
      $row = mysqli_fetch_assoc($result);

      if($password === $row["password"]){
          header("Location: admin_chart.php");
          exit;
      }
  }

  $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Form</title>
    <link rel="website icon" type="image/jpeg" href="AsetFoto/Login/rins_logo.png">
    <link rel="stylesheet" href="css/tailwind.css">
  </head>
  <body class="bg-cover bg-center bg-no-repeat h-screen" style="background-image: url('./AsetFoto/Login/Toko Rins.jpg')">
    <!--Navbar-->
    <nav class="bg-red-700 p-4 flex justify-between items-center sticky top-0 z-50">
      <img src="./AsetFoto/Produk/LOGO_RINS.png" class="w-28 h-10" />

      <button onclick="window.location.href='home_customer.php'" class="bg-gray-200 text-red-700 rounded-full px-4 py-2 font-semibold hover:bg-gray-300 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
         <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
        </svg>

    </button>
    </nav>
    <!--Navbar-->

    <div class="flex items-center justify-center h-full bg-gray-900 bg-opacity-60">
      <div class="bg-white bg-opacity-90 rounded-lg shadow-lg p-8 w-full max-w-md">
        <div class="flex justify-center mb-6">
          <img src="AsetFoto/Login/rins_logo.png" alt="Your Company" class="w-32 h-32" />
        </div>
        <form action="" method="post">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
            <div class="relative">
              <input type="text" id="username" name="username" placeholder="Masukkan Username..." class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
          </div>
          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
            <div class="relative">
              <input type="password" name="password" id="password" placeholder="Masukkan Password..." class="appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <?php if(isset($error)) : ?>
              <p style="color: red;">
                Username/password salah
              </p>
              <?php endif; ?>
          </div>
          <div class="flex items-center justify-center">
          <button type="submit" name="login" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline">LOGIN</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
