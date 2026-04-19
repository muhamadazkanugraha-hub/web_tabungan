<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['user'];
$ambil_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id_user'");
$data_user = mysqli_fetch_assoc($ambil_user);

$nama_lengkap = $data_user['nama'];
$email_user = $data_user['email'];
$tanggal_daftar = isset($data_user['created_at']) ? date('d F Y', strtotime($data_user['created_at'])) : "31 May 2025";
$inisial = strtoupper(substr($nama_lengkap, 0, 1));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Pengguna - Tabunganku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f7fe;
            color: #333;
        }

        .navbar {
            background: #0b0b3b;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .btn-user-menu {
            background: none;
            border: none;
            color: white;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: block;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 150px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
            z-index: 1;
            border: 1px solid #0b0b3b;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease-in-out;
        }

        .dropdown:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-content a {
            color: #0b0b3b;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 13px;
            font-weight: bold;
        }

        .container {
            padding: 50px 5% 20px 5%;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 80vh;
        }

        .profile-card {
            background: white;
            width: 100%;
            max-width: 700px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .avatar-circle {
            width: 70px;
            height: 70px;
            background: #4f46e5;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 15px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .btn-back {
            display: inline-block;
            background: #4f46e5;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 30px;
            float: right;
        }

        .footer {
            text-align: center;
            padding: 40px;
            color: #888;
            font-size: 14px;
            margin-top: 50px;
            border-top: 1px solid #ddd;
            width: 100%;
        }

        .logout-link {
            color: #ff4757 !important;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="TABUNG.png" height="35">
        <h3 style="margin:0">Tabunganku</h3>
    </div>
    
    <div class="dropdown">
        <button class="btn-user-menu">
            <span>Dashboard</span>
            <span style="font-size: 10px; opacity: 0.8;">▼</span>
        </button>
        <div class="dropdown-content">
            <a href="home.php">BERANDA</a>
            <a href="logout.php" class="logout-link">LOGOUT</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="profile-card">
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
            <div class="avatar-circle"><?= $inisial; ?></div>
            <div>
                <h2 style="margin:0; color:#0b0b3b;">Profil Pengguna</h2>
                <p style="margin:5px 0 0 0; color:#888; font-size:14px;">Informasi akun kamu</p>
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td style="color:#888; width:40%;">Nama Lengkap</td>
                <td style="font-weight:500;"><?= $nama_lengkap; ?></td>
            </tr>
            <tr>
                <td style="color:#888;">Email</td>
                <td style="font-weight:500;"><?= $email_user; ?></td>
            </tr>
            <tr>
                <td style="color:#888;">Terdaftar Sejak</td>
                <td style="font-weight:500;"><?= $tanggal_daftar; ?></td>
            </tr>
        </table>

        <a href="home.php" class="btn-back">← Kembali ke Home</a>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        &copy; 2026 <b>Tabunganku</b> - Dibuat oleh Azka
    </div>
</div>

</body>
</html>