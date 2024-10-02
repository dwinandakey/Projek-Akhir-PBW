<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800,900">
    <link rel="stylesheet" href="./styles/my-account.css">
</head>

<body>
    <nav class="navigasi">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="about-us.php">About Us</a></li>
            <li><a href="review.php">Reviews</a></li>
            <li><a href="contact-us.php">Contact Us</a></li>
        </ul>
    </nav>

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="register.php" method="post" onsubmit="return validateForm()">
                <h1>Register</h1>
                <span>Isikan data dengan valid</span>
                <input type="text" name="nama" placeholder="Nama Lengkap" required>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="text" id="username" name="username" placeholder="Username" required>
                <input type="text" name="alamat" placeholder="Alamat" required>
                <input type="tel" id="telp" name="telp" placeholder="No Telp" required>
                <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                <button type="submit">Register</button>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form action="login.php" method="post">
                <h1>Login</h1>
                <span>Gunakan akun Anda</span>
                <input type="username" placeholder="Username" name="username" required>
                <input type="password" placeholder="Password" name="password" required style="flex-direction:row;">
                <button type="submit" name="login">Login</button> <br>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>NatureGear</h1>
                    <p>Mohon isikan data diri asli secara benar</p>
                    <button class="ghost" id="signIn">Login</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Selamat datang</h1>
                    <p>Silahkan melakukan registrasi terlebih dahulu jika tidak mempunyai akun</p>
                    <button class="ghost" id="signUp">Belum punya akun</button>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="social-media">
            <a href="https://web.facebook.com/NatureGear/"><img src="./images/facebook-icon.png" alt="Facebook"></a>
            <a href="https://web.twitter.com/NatureGear/"><img src="./images/twitter-icon.png" alt="Twitter"></a>
            <a href="https://web.instagram.com/NatureGear/"><img src="./images/instagram-icon.png" alt="Instagram"></a>
        </div>
        <p>&copy; 2024 NatureGear. All rights reserved.</p>
    </footer>
</body>

</html>
<script src="js/my-account.js"></script>