<?php
include "config.php";

if (isset($_POST['register'])) {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $password = md5($_POST['password']);

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($cek) > 0) {
        $error = "Email ini sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (nama, email, password) 
                  VALUES ('$nama', '$email', '$password')";

        if (mysqli_query($koneksi, $query)) {
            header("Location: login.php?pesan=berhasil");
            exit();
        } else {
            $error = "Gagal mendaftar, coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun - Tabunganku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            background: #0b0b3b;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background: white;
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin: 20px 0;
        }

        .logo-area {
            margin-bottom: 20px;
        }

        .logo-area img {
            width: 60px;
        }

        h2 {
            margin: 0 0 10px 0;
            color: #0b0b3b;
        }

        p.subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 12px;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #0b0b3b;
            outline: none;
            box-shadow: 0 0 5px rgba(11, 11, 59, 0.2);
        }

        button {
            width: 100%;
            padding: 14px;
            background: #0b0b3b;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover {
            background: #1a1a5e;
            transform: translateY(-2px);
        }

        .error-msg {
            color: #e74c3c;
            background: #fdeaea;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #fadbd8;
        }

        .footer-link {
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .footer-link a {
            color: #0b0b3b;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="register-container">
    <div class="logo-area">
        <img src="TABUNG.png" alt="Logo">
    </div>

    <h2>Buat Akun</h2>
    <p class="subtitle">
        Mulai kelola tabungan impianmu sekarang.
    </p>

    <?php if (isset($error)) { ?>
        <div class="error-msg">
            <?= $error; ?>
        </div>
    <?php } ?>

    <form method="POST">
        <div class="input-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Masukkan nama" required>
        </div>

        <div class="input-group">
            <label>Alamat Email</label>
            <input type="email" name="email" placeholder="contoh@email.com" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Minimal 6 karakter" required>
        </div>

        <button name="register">
            Daftar Sekarang
        </button>
    </form>

    <div class="footer-link">
        Sudah punya akun?
        <a href="login.php">Login di sini</a>
    </div>
</div>

</body>
</html>