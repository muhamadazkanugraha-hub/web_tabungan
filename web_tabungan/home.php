<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id_user   = $_SESSION['user'];
$ambil_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id_user'");
$data_user  = mysqli_fetch_assoc($ambil_user);
$nama_user  = $data_user['nama'];

$total_semua_target = 0;
$total_semua_saldo  = 0;

$query_hitung = mysqli_query($koneksi, "SELECT * FROM tabungan WHERE user_id='$id_user'");
while ($row = mysqli_fetch_assoc($query_hitung)) {
    $id_tab = $row['id'];

    $res_saldo  = mysqli_query($koneksi, "SELECT SUM(nominal) as total FROM menabung WHERE tabungan_id='$id_tab'");
    $data_saldo = mysqli_fetch_assoc($res_saldo);
    $saldo_skrg = $data_saldo['total'] ?? 0;

    $total_semua_target += $row['target_nominal'];
    $total_semua_saldo  += $saldo_skrg;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Tabunganku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            transition: all 0.3s ease;
        }

        body {
            margin: 0;
            background: #f4f7fe;
            color: #333;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* ANIMASI */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade {
            animation: fadeInUp 0.8s ease forwards;
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

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .btn-user-menu {
            background: none;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
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

        .dropdown-content a:hover {
            background: #f4f7fe;
        }

        .container {
            padding: 20px 5%;
            max-width: 1200px;
            margin: auto;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin: 20px 0 40px 0;
        }

        @media (min-width: 768px) {
            .summary-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-bottom: 4px solid #0b0b3b;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card small {
            color: #888;
            text-transform: uppercase;
            font-size: 11px;
        }

        .stat-card h2 {
            margin: 10px 0 0 0;
            color: #0b0b3b;
            font-size: 1.5rem;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .btn-add {
            background: #0b0b3b;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-add:hover {
            background: #1e1e63;
            transform: scale(1.05);
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            z-index: 1;
        }

        .card-body {
            padding: 20px;
        }

        .progress-container {
            background: #edf2f7;
            height: 8px;
            border-radius: 10px;
            margin: 15px 0 8px 0;
            overflow: hidden;
        }

        .progress-bar {
            background: #2ecc71;
            height: 100%;
            border-radius: 10px;
            transition: width 1.5s ease-in-out;
        }

        .card-actions {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 8px;
            margin-top: 20px;
        }

        .btn-action {
            padding: 10px 5px;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        .btn-main {
            background: #0b0b3b;
            color: white;
        }

        .btn-main:hover {
            background: #1e1e63;
        }

        .btn-edit {
            background: #f1c40f;
            color: white;
        }

        .btn-delete {
            background: #ff4757;
            color: white;
        }

        .footer {
            text-align: center;
            padding: 40px;
            color: #888;
            font-size: 14px;
            margin-top: 50px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div style="display: flex; align-items: center; gap: 10px;">
        <img src="TABUNG.png" height="30" alt="Logo">
        <h3 style="margin: 0; font-size: 18px;">Tabunganku</h3>
    </div>

    <div class="dropdown">
        <button class="btn-user-menu">
            Menu <span style="font-size: 10px;">▼</span>
        </button>

        <div class="dropdown-content">
            <a href="profile.php">PROFIL</a>
            <a href="logout.php" style="color: red;">LOGOUT</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="animate-fade">
        <h1 style="margin: 0; font-size: 1.8rem;">
            Halo, <?= $nama_user; ?>!
        </h1>

        <p style="color: #666; margin: 5px 0 0 0;">
            Cek perkembangan target tabunganmu hari ini.
        </p>

        <h3 style="border-left: 5px solid #0b0b3b; padding-left: 15px; margin-top: 30px;">
            Ringkasan Tabungan
        </h3>

        <div class="summary-grid">
            <div class="stat-card">
                <small>Total Tabungan</small>
                <h2 class="counter" data-target="<?= $total_semua_saldo; ?>">0</h2>
            </div>

            <div class="stat-card">
                <small>Total Kebutuhan</small>
                <h2 class="counter" data-target="<?= $total_semua_target; ?>">0</h2>
            </div>

            <div class="stat-card">
                <small>Persentase Total</small>
                <?php
                $total_persen = ($total_semua_target > 0)
                    ? ($total_semua_saldo / $total_semua_target) * 100
                    : 0;
                ?>
                <h2 class="counter" data-target="<?= round($total_persen); ?>">0</h2>
            </div>
        </div>

        <div class="content-header">
            <h3 style="margin: 0; border-left: 5px solid #0b0b3b; padding-left: 15px;">
                Daftar Impian
            </h3>

            <a href="tambah_tabungan.php" class="btn-add">+ Tambah</a>
        </div>
    </div>

    <div class="wishlist-grid animate-fade">
        <?php
        $ambil_tabungan = mysqli_query($koneksi, "SELECT * FROM tabungan WHERE user_id='$id_user' ORDER BY id DESC");

        while ($t = mysqli_fetch_assoc($ambil_tabungan)) {
            $id_tab = $t['id'];

            $res_saldo  = mysqli_query($koneksi, "SELECT SUM(nominal) as total FROM menabung WHERE tabungan_id='$id_tab'");
            $data_saldo = mysqli_fetch_assoc($res_saldo);
            $saldo_skrg = $data_saldo['total'] ?? 0;

            $persen   = ($t['target_nominal'] > 0) ? ($saldo_skrg / $t['target_nominal']) * 100 : 0;
            $is_lunas = ($saldo_skrg >= $t['target_nominal']);
        ?>
        <div class="card">
            <div class="status-badge" style="background: <?= $is_lunas ? '#2ecc71' : '#3498db' ?>;">
                <?= $is_lunas ? 'Tercapai' : 'Proses' ?>
            </div>

            <img src="uploads/<?= $t['foto']; ?>" class="card-img">

            <div class="card-body">
                <div style="font-weight: bold; font-size: 17px; color: #0b0b3b;">
                    <?= $t['judul']; ?>
                </div>

                <div style="font-size: 13px; color: #777; margin-top: 5px;">
                    Target: Rp <?= number_format($t['target_nominal'], 0, ',', '.'); ?>
                </div>

                <div class="progress-container">
                    <div class="progress-bar" style="width: <?= min($persen, 100); ?>%"></div>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold;">
                    <span style="color: #2ecc71;">
                        Rp <?= number_format($saldo_skrg, 0, ',', '.'); ?>
                    </span>
                    <span><?= round($persen); ?>%</span>
                </div>

                <div class="card-actions">
                    <a href="menabung.php?id=<?= $t['id']; ?>"
                       class="btn-action btn-main"
                       style="background: <?= $is_lunas ? '#27ae60' : '#0b0b3b' ?>;">
                        <?= $is_lunas ? 'Lihat' : 'Nabung' ?>
                    </a>

                    <a href="edit_tabungan.php?id=<?= $t['id']; ?>" class="btn-action btn-edit">
                        Edit
                    </a>

                    <button onclick="hapusImpian(<?= $t['id']; ?>)" class="btn-action btn-delete">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="footer">
        &copy; 2026 <b>Tabunganku</b> - Dibuat oleh Azka
    </div>
</div>

<script>
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count  = +counter.innerText.replace(/[^0-9]/g, '');
            const increment = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + increment).toLocaleString('id-ID');
                setTimeout(updateCount, 10);
            } else {
                counter.innerText =
                    target.toLocaleString('id-ID') +
                    (counter.parentElement.querySelector('small').innerText.includes('Persentase') ? '%' : '');
            }
        };
        updateCount();
    });

    function hapusImpian(id) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data impian dan semua riwayat tabunganmu bakal hilang selamanya loh, Ka Azka!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0b0b3b',
            cancelButtonColor: '#ff4757',
            confirmButtonText: 'Ya, Hapus Saja!',
            cancelButtonText: 'Nggak Jadi'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "hapus_tabungan.php?id=" + id;
            }
        });
    }
</script>

</body>
</html>