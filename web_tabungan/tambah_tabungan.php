<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $id_user = $_SESSION['user'];
    $judul = $_POST['judul'];
    $target_nominal = $_POST['target_nominal'];
    $target_tanggal = $_POST['target_tanggal'];
    $hari_ini = date('Y-m-d');

    if ($target_tanggal < $hari_ini) {
        $error = "Eits! Tanggal target nggak boleh masa lalu. Pilih masa depan ya!";
    } else {
        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        
        $foto_baru = date('dmYHis') . $foto;
        $path = "uploads/" . $foto_baru;

        if (move_uploaded_file($tmp, $path)) {
            $query = "INSERT INTO tabungan (user_id, foto, judul, target_nominal, target_tanggal) 
                      VALUES ('$id_user', '$foto_baru', '$judul', '$target_nominal', '$target_tanggal')";
            
            if (mysqli_query($koneksi, $query)) {
                header("Location: home.php");
                exit();
            } else {
                $error = "Gagal menyimpan ke database.";
            }
        } else {
            $error = "Gagal mengupload gambar.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Impian - Tabunganku</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 550px;
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
            transition: 0.3s;
        }

        input:focus {
            border-color: #0b0b3b;
            outline: none;
            box-shadow: 0 0 0 3px rgba(11,11,59,0.1);
        }

        .btn-save {
            width: 100%;
            padding: 14px;
            background: #0b0b3b;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-save:hover {
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

        .logout-link { color: #ff4757 !important; }
    </style>
</head>
<body>

<nav class="navbar">
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="TABUNG.png" height="35">
        <h3 style="margin:0; color: white;">Tabunganku</h3>
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
        <h2 style="color: #0b0b3b; margin-top: 0;">✨ Tambah Impian</h2>
        <p style="color: #777; font-size: 14px; margin-bottom: 30px;">Tentukan target tabungan baru kamu hari ini.</p>

        <?php if(isset($error)): ?>
            <div style="background: #fff5f5; color: #e53e3e; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; border: 1px solid #fed7d7;">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>Foto Barang/Tujuan</label>
                <input type="file" name="foto" required style="padding: 10px;">
            </div>

            <div class="input-group">
                <label>Judul Tabungan (Misal: Beli iPhone)</label>
                <input type="text" name="judul" placeholder="Mau beli apa?" required>
            </div>

            <div class="input-group">
                <label>Target Nominal (Rp)</label>
                <input type="number" name="target_nominal" placeholder="Contoh: 5000000" required>
            </div>

            <div class="input-group">
                <label>Target Tanggal Tercapai</label>
                <input type="date" name="target_tanggal" id="tanggal_target" required>
            </div>

            <button type="submit" name="simpan" class="btn-save">Simpan Impian</button>
            <a href="home.php" class="btn-back">← Kembali ke Dashboard</a>
        </form>
    </div>

    <div class="footer">
        &copy; 2026 <b>Tabunganku</b> - Dibuat oleh Azka
    </div>
</div>

<script>
    const today = new Date().toISOString().split('T')[0];    
    document.getElementById('tanggal_target').setAttribute('min', today);
</script>

</body>
</html>