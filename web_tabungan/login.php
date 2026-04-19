<?php
session_start();
include "config.php";

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $data  = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['user'] = $data['id'];
        header("Location: home.php");
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Masuk - Tabunganku</title>
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

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 380px;
            text-align: center;
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
            margin-bottom: 15px;
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

        .success-msg {
            color: #27ae60;
            background: #eafaf1;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #d4efdf;
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

<div class="login-container">
    <div class="logo-area">
        <img src="TABUNG.png" alt="Logo">
    </div>

    <h2>Selamat Datang</h2>
    <p class="subtitle">Masuk untuk cek progres impianmu.</p>

    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil') { ?>
        <div class="success-msg">
            Pendaftaran berhasil! Silakan login.
        </div>
    <?php } ?>

    <?php if (isset($error)) { ?>
        <div class="error-msg">
            <?= $error; ?>
        </div>
    <?php } ?>

    <form method="POST">
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>

        <button type="submit" name="login">
            Masuk ke Dashboard
        </button>
    </form>

    <div class="footer-link">
        Belum punya akun?
        <a href="register.php">Daftar Sekarang</a>
    </div>
</div>

</body>
</html>