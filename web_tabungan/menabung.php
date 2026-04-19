<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id        = $_GET['id'];
$id_user   = $_SESSION['user'];
$notif     = false;
$error_type = "";
$hari_ini  = date('Y-m-d');

// 1. Ambil data tabungan
$data = mysqli_query($koneksi, "SELECT * FROM tabungan WHERE id='$id' AND user_id='$id_user'");
$t    = mysqli_fetch_assoc($data);

// 2. Ambil total saldo
$m = mysqli_query($koneksi, "SELECT CAST(SUM(nominal) AS UNSIGNED) as total FROM menabung WHERE tabungan_id='$id'");
$d = mysqli_fetch_assoc($m);
$total = intval($d['total'] ?? 0);

if (isset($_POST['tambah'])) {
    $nominal_input = $_POST['nominal'];

    // CEK minus
    if (strpos($nominal_input, '-') !== false) {
        $error_type = "minus";
    } else {
        $nominal_raw = preg_replace("/[^0-9]/", "", $nominal_input);
        $nominal     = intval($nominal_raw);
        $tanggal     = $_POST['tanggal'];

        $target_nominal = intval($t['target_nominal']);
        $sisa_target    = $target_nominal - $total;

        // VALIDASI
        if ($nominal <= 0) {
            $error_type = "minus";
        } elseif ($nominal > $sisa_target) {
            $error_type    = "over";
            $nominal_format = number_format($nominal, 0, ',', '.');
            $sisa_format    = number_format($sisa_target, 0, ',', '.');
        } elseif ($total >= $target_nominal) {
            $error_type = "full";
        } elseif ($tanggal != $hari_ini) {
            $error_type = "date";
        } else {
            $query_insert = "INSERT INTO menabung (tabungan_id, nominal, tanggal) 
                             VALUES ('$id', '$nominal_raw', '$tanggal')";

            if (mysqli_query($koneksi, $query_insert)) {
                header("Location: menabung.php?id=$id&status=success");
                exit();
            }
        }
    }
}

if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $notif = true;
}

$persen = ($t['target_nominal'] > 0)
    ? ($total / $t['target_nominal']) * 100
    : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Proses Menabung - Tabunganku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            padding: 30px 5%;
            max-width: 1100px;
            margin: auto;
        }

        .card-header {
            background: white;
            padding: 25px;
            border-radius: 20px;
            display: flex;
            gap: 25px;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .header-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .footer {
            text-align: center;
            padding: 40px;
            color: #888;
            font-size: 14px;
            margin-top: 50px;
            border-top: 1px solid #ddd;
        }

        .logout-link {
            color: #ff4757 !important;
        }

        @media (max-width: 768px) {
            .main-grid {
                grid-template-columns: 1fr;
            }

            .card-header {
                flex-direction: column;
                text-align: center;
            }
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
    <div class="card-header">
        <img src="uploads/<?= $t['foto'] ?>" class="header-img">

        <div style="flex: 1;">
            <h2 style="margin: 0; color: #0b0b3b;">
                <?= $t['judul'] ?>
            </h2>

            <div style="background: #eee; height: 12px; border-radius: 10px; margin: 15px 0; overflow: hidden;">
                <div style="background: #2ecc71; height: 100%; width: <?= min($persen, 100); ?>%"></div>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 14px;">
                <span>
                    Terkumpul:
                    <b style="color: #2ecc71;">
                        Rp <?= number_format($total, 0, ',', '.') ?>
                    </b>
                </span>
                <span style="color: #888;">
                    Target: Rp <?= number_format($t['target_nominal'], 0, ',', '.') ?>
                </span>
            </div>
        </div>
    </div>

    <div class="main-grid">
        <!-- FORM -->
        <div class="card">
            <h3 style="margin-top: 0; border-left: 4px solid #0b0b3b; padding-left: 10px;">
                Tambah Saldo
            </h3>

            <?php if ($total < $t['target_nominal']) : ?>
                <form method="POST">
                    <label style="display: block; font-weight: bold; font-size: 13px; margin-bottom: 8px;">
                        Nominal Uang (Rp)
                    </label>

                    <input type="text"
                           name="nominal"
                           inputmode="numeric"
                           placeholder="Contoh: 500000"
                           required
                           autofocus
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           style="width:100%; padding:14px; margin-bottom:20px; border:1px solid #ddd; border-radius:12px;">

                    <label style="display:block; font-weight:bold; font-size:13px; margin-bottom:8px;">
                        Tanggal Transaksi
                    </label>

                    <input type="date"
                           name="tanggal"
                           value="<?= $hari_ini ?>"
                           readonly
                           style="width:100%; padding:14px; margin-bottom:20px; border:1px solid #ddd; border-radius:12px; background:#f9f9f9; cursor:not-allowed;">

                    <div style="display:flex; gap:12px;">
                        <button name="tambah"
                                style="flex:2; padding:14px; background:#0b0b3b; color:white; border:none; border-radius:12px; font-weight:bold; cursor:pointer;">
                            Simpan ke Celengan
                        </button>

                        <a href="home.php"
                           style="flex:1; display:flex; align-items:center; justify-content:center; text-decoration:none; background:#f1f2f6; color:#555; border-radius:12px; font-weight:bold; border:1px solid #ddd;">
                            Batal
                        </a>
                    </div>
                </form>
            <?php else : ?>
                <div style="text-align:center; padding:30px; border:2px dashed #2ecc71; border-radius:20px; background:#f0fff4;">
                    <p style="color:#27ae60; font-weight:bold; margin:10px 0;">
                        Target Tercapai! 🎉
                    </p>

                    <a href="home.php"
                       style="margin-top:15px; display:inline-block; padding:10px 20px; background:#0b0b3b; color:white; text-decoration:none; border-radius:10px;">
                        Kembali
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- RIWAYAT -->
        <div class="card">
            <h3 style="margin-top:0; border-left:4px solid #0b0b3b; padding-left:10px;">
                Riwayat
            </h3>

            <div style="max-height:350px; overflow-y:auto;">
                <?php
                $data2 = mysqli_query($koneksi, "SELECT * FROM menabung WHERE tabungan_id='$id' ORDER BY tanggal DESC, id DESC");

                while ($r = mysqli_fetch_assoc($data2)) {
                ?>
                    <div style="display:flex; justify-content:space-between; padding:15px 0; border-bottom:1px dashed #eee;">
                        <span style="color:#888; font-size:13px;">
                            <?= date('d M Y', strtotime($r['tanggal'])) ?>
                        </span>

                        <b style="color:#2ecc71;">
                            + Rp <?= number_format($r['nominal'], 0, ',', '.') ?>
                        </b>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; 2026 <b>Tabunganku</b> - Dibuat oleh Azka
    </div>
</div>

<script>
const swalConfig = { confirmButtonColor: '#0b0b3b' };

<?php if ($notif): ?>
    Swal.fire({
        ...swalConfig,
        icon: 'success',
        title: 'Mantap!',
        text: 'Uang berhasil ditabung.'
    });
<?php endif; ?>

<?php if ($error_type == 'minus'): ?>
    Swal.fire({
        ...swalConfig,
        icon: 'error',
        title: 'Input Ilegal!',
        text: 'Waduh, nabung kok minus? Masukin angka yang bener ya Ka Azka!'
    });
<?php endif; ?>

<?php if ($error_type == 'over'): ?>
    Swal.fire({
        ...swalConfig,
        icon: 'warning',
        title: 'Waduh!',
        html: 'Nominal Rp <b><?= $nominal_format ?></b> kegedean.<br>Sisa target Rp <b><?= $sisa_format ?></b>.'
    });
<?php endif; ?>
</script>

</body>
</html>