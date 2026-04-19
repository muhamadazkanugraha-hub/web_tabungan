<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id        = $_GET['id'];
$id_user   = $_SESSION['user'];
$hari_ini  = date('Y-m-d');

// 1. Ambil data lama tabungan
$query = mysqli_query($koneksi, "SELECT * FROM tabungan WHERE id='$id' AND user_id='$id_user'");
$data  = mysqli_fetch_assoc($query);

// 2. Ambil total saldo yang sudah terkumpul saat ini
$query_saldo   = mysqli_query($koneksi, "SELECT SUM(nominal) as total FROM menabung WHERE tabungan_id='$id'");
$data_saldo    = mysqli_fetch_assoc($query_saldo);
$saldo_sekarang = intval($data_saldo['total'] ?? 0);

if (isset($_POST['update'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);

    // Bersihkan input dari karakter non-angka
    $target_nominal_raw = preg_replace("/[^0-9]/", "", $_POST['target_nominal']);
    $target_nominal     = intval($target_nominal_raw);

    $target_tanggal = $_POST['target_tanggal'];

    // VALIDASI 1: Tanggal tidak boleh lewat
    if ($target_tanggal < $hari_ini) {
        echo "<script>
                alert('Gagal! Tanggal target tidak boleh tanggal yang sudah lewat.');
                window.location='edit_tabungan.php?id=$id';
              </script>";
        exit();
    }

    // VALIDASI 2: Target tidak boleh lebih kecil dari saldo
    if ($target_nominal < $saldo_sekarang) {
        $saldo_format = number_format($saldo_sekarang, 0, ',', '.');

        echo "<script>
                alert('Gagal! Target baru (Rp " . number_format($target_nominal, 0, ',', '.') . ") tidak boleh lebih kecil dari saldo yang sudah terkumpul (Rp $saldo_format).');
                window.location='edit_tabungan.php?id=$id';
              </script>";
        exit();
    }

    // Cek upload foto
    if ($_FILES['foto']['name'] != "") {
        $foto      = $_FILES['foto']['name'];
        $tmp       = $_FILES['foto']['tmp_name'];
        $foto_baru = date('dmYHis') . "_" . $foto;

        move_uploaded_file($tmp, "uploads/" . $foto_baru);

        $sql = "UPDATE tabungan 
                SET judul='$judul',
                    target_nominal='$target_nominal',
                    target_tanggal='$target_tanggal',
                    foto='$foto_baru'
                WHERE id='$id'";
    } else {
        $sql = "UPDATE tabungan 
                SET judul='$judul',
                    target_nominal='$target_nominal',
                    target_tanggal='$target_tanggal'
                WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
                alert('Perubahan berhasil disimpan!');
                window.location='home.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Impian - Tabunganku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
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
            padding: 50px 5%;
            max-width: 1200px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 80vh;
        }

        .form-card {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 500px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 14px;
        }

        .img-preview {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .img-old {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-update {
            width: 100%;
            padding: 14px;
            background: #0b0b3b;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-update:hover {
            background: #1a1a5e;
            transform: translateY(-2px);
        }

        .btn-back {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #888;
            font-size: 14px;
            margin-top: 20px;
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
        <h3 style="margin: 0; color: white;">Tabunganku</h3>
    </div>

    <div class="dropdown">
        <button class="btn-user-menu">
            <span>Dashboard</span>
            <span style="font-size: 10px; opacity: 0.8;">▼</span>
        </button>

        <div class="dropdown-content">
            <a href="home.php">BERANDA</a>
            <a href="profile.php">PROFIL</a>
            <a href="logout.php" class="logout-link">LOGOUT</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="form-card">
        <h2 style="color: #0b0b3b; margin-top: 0;">Edit Impian</h2>

        <div style="background: #eef2ff; padding: 10px; border-radius: 10px; margin-bottom: 20px;">
            <span style="font-size: 12px; color: #555;">Saldo Terkumpul Saat Ini:</span><br>
            <b style="color: #2ecc71;">
                Rp <?= number_format($saldo_sekarang, 0, ',', '.') ?>
            </b>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>Nama Impian</label>
                <input type="text" name="judul" value="<?= $data['judul'] ?>" required>
            </div>

            <div class="input-group">
                <label>Target Nominal (Rp)</label>
                <input type="text"
                       inputmode="numeric"
                       name="target_nominal"
                       value="<?= $data['target_nominal'] ?>"
                       required
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <small style="font-size: 10px; color: #999;">
                    *Minimal sama dengan saldo saat ini
                </small>
            </div>

            <div class="input-group">
                <label>Target Tanggal</label>
                <input type="date"
                       name="target_tanggal"
                       value="<?= $data['target_tanggal'] ?>"
                       min="<?= $hari_ini ?>"
                       required>
            </div>

            <div class="input-group">
                <label>Ganti Foto (Opsional)</label>
                <input type="file" name="foto" accept="image/*" style="padding: 10px;">

                <div class="img-preview">
                    <img src="uploads/<?= $data['foto'] ?>" class="img-old">
                    <span style="font-size: 11px; color: #777;">Foto saat ini</span>
                </div>
            </div>

            <button type="submit" name="update" class="btn-update">
                Update Impian
            </button>

            <a href="home.php" class="btn-back">
                Batal & Kembali
            </a>
        </form>
    </div>

    <div class="footer">
        &copy; 2026 <b>Tabunganku</b> - Dibuat oleh Azka Nugraha untuk Tes PKL
    </div>
</div>

</body>
</html>