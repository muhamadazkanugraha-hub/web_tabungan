<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit(); 
}
$id_user = $_SESSION['user'];
$hari_ini = date('Y-m-d'); 

if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $target_nominal = $_POST['target_nominal'];
    $target_tanggal = $_POST['target_tanggal'];

    if ($target_tanggal < $hari_ini) {
        echo "<script>alert('Gagal! Tanggal target tidak boleh tanggal yang sudah lewat.'); window.location='tabungan.php';</script>";
        exit();
    }

    $foto = "";
    if ($_FILES['foto']['name'] == "") {
        echo "<script>alert('Gagal! Kamu harus mengunggah foto target impianmu.'); window.location='tabungan.php';</script>";
        exit();
    }

    $foto = time().'_'.$_FILES['foto']['name'];
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/".$foto);

    mysqli_query($koneksi,"INSERT INTO tabungan VALUES(NULL,'$id_user','$judul','$foto','$target_nominal','$target_tanggal','0')");
    header("Location: tabungan.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $target_nominal = $_POST['target_nominal'];
    $target_tanggal = $_POST['target_tanggal'];

    if ($target_tanggal < $hari_ini) {
        echo "<script>alert('Gagal! Tanggal target tidak boleh tanggal yang sudah lewat.'); window.location='tabungan.php';</script>";
        exit();
    }

    mysqli_query($koneksi,"UPDATE tabungan SET judul='$judul', target_nominal='$target_nominal', target_tanggal='$target_tanggal' WHERE id='$id'");
    header("Location: tabungan.php");
}

$data = mysqli_query($koneksi, "SELECT * FROM tabungan WHERE user_id='$id_user'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tabungan - Tabunganku</title>
    <style>
        * { box-sizing: border-box; font-family: sans-serif; }
        body { margin: 0; display: flex; background: #f0f2f5; min-height: 100vh; }

        .sidebar {
            width: 200px;
            background: #0b0b3b;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
            z-index: 100;
        }
        
        .sidebar a { 
            color: white; 
            text-decoration: none; 
            display: block; 
            margin: 20px 0; 
            opacity: 0.8; 
        }

        .sidebar a.active { 
            opacity: 1; font-weight: bold; 
            border-left: 3px solid #fff; 
            padding-left: 10px; 
        }

        .main {
            margin-left: 200px;
            padding: 20px;
            width: calc(100% - 200px);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header-user {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: -20px -20px 30px -20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #e1e4e8;
        }

        .btn-tambah {
            background: #0b0b3b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        th { 
            background: #f8f9fa; 
            padding: 15px; 
            border-bottom: 2px solid #eee; 
            text-align: center; 
            color: #0b0b3b; 
        }

        td { 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            text-align: center; 
        }

        .btn-edit {
            background: #3498db; 
            color: white; 
            border: none; 
            padding: 6px 12px; 
            border-radius: 6px; 
            cursor: pointer; 
        }
        .btn-hapus { 
            background: #ff4757; 
            color: white; 
            border: none; 
            padding: 6px 12px; 
            border-radius: 6px; 
            cursor: pointer; 
            text-decoration: none; 
            font-size: 13px; 
        }

        .footer {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: auto -20px -20px -20px; 
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.05);
            border-top: 1px solid #d1d8e0;
            color: #636e72;
            font-size: 14px;
        }

        .modal { 
            display:none; 
            position:fixed; 
            top:0; 
            left:0; 
            width:100%; 
            height:100%; 
            background:rgba(0,0,0,0.5); 
            justify-content:center; 
            align-items:center; 
            z-index: 1000; 
        }

        .modal-content { 
            background:white; 
            width:400px; 
            padding:25px; 
            border-radius:12px; 
        }

        .modal-content label {
            font-size: 12px;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }

        .modal-content input { 
            width:100%; 
            padding:10px; 
            margin-bottom:10px; 
            border:1px solid #ccc; 
            border-radius:6px; 
        }

        .modal-content button { 
            width:100%; 
            padding:10px; 
            border:none; 
            border-radius:6px; 
            background:#0b0b3b; 
            color:white; 
            cursor:pointer; 
            margin-top: 5px; 
        }

        .btn-close { 
            background: #95a5a6 !important; 
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 30px;">
            <img src="TABUNG.png" alt="Logo" style="width: 40px; height: 40px; object-fit: contain;">
            <h3 style="margin: 0; color: white;">Tabunganku</h3>
        </div>
        <a href="home.php">Home</a>
        <a href="tabungan.php" class="active">Tabungan</a>
        <a href="logout.php">Keluar</a>
    </div>

    <div class="main">
        <div class="header-user">
            <div>
                <h2 style="margin:0;">Manajemen Tabungan</h2>
                <p style="margin:5px 0 0 0; color: #636e72;">Tambah, ubah, atau hapus target impianmu.</p>
            </div>
            <button class="btn-tambah" onclick="openTambah()">+ Tambah Tabungan</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Target</th>
                        <th>Tanggal</th>
                        <th>Terkumpul</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($t = mysqli_fetch_assoc($data)) { 
                        $m = mysqli_query($koneksi,"SELECT SUM(nominal) as total FROM menabung WHERE tabungan_id=".$t['id']);
                        $d = mysqli_fetch_assoc($m);
                        $total = $d['total'] ?? 0;
                        $status = ($total >= $t['target_nominal']) ? "✅ Tercapai" : "⏳ Proses";
                    ?>
                    <tr>
                        <td><img src="uploads/<?= $t['foto'] ?>" width="60" style="border-radius: 5px;"></td>
                        <td><b><?= $t['judul'] ?></b></td>
                        <td>Rp <?= number_format($t['target_nominal'],0,',','.') ?></td>
                        <td><?= date('d M Y', strtotime($t['target_tanggal'])) ?></td>
                        <td>Rp <?= number_format($total,0,',','.') ?></td>
                        <td><i><?= $status ?></i></td>
                        <td>
                            <button class="btn-edit" onclick="openEdit('<?= $t['id'] ?>','<?= $t['judul'] ?>','<?= $t['target_nominal'] ?>','<?= $t['target_tanggal'] ?>')">Edit</button>
                            <a href="hapus_tabungan.php?id=<?= $t['id'] ?>" onclick="return confirm('Yakin mau hapus?')" class="btn-hapus">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div>&copy; 2026 <span style="color:#0b0b3b; font-weight:bold;">Tabunganku</span> - Kelola Impianmu.</div>
            <div>Dibuat dengan  oleh <b>Azka Nugraha</b></div>
        </div>
    </div>

    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <h3>Tambah Tabungan</h3>
            <form method="POST" enctype="multipart/form-data">
                <input name="judul" placeholder="Judul Tabungan" required>
                <input name="target_nominal" type="number" placeholder="Target Nominal (Angka)" required>
                <label>Target Tanggal (Minimal Hari Ini)</label>
                <input type="date" name="target_tanggal" id="input_tgl_tambah" required>
                <input type="file" name="foto" required>
                <button name="simpan">Simpan Tabungan</button>
                <button type="button" class="btn-close" onclick="closeModal()">Batal</button>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <h3>Edit Tabungan</h3>
            <form method="POST">
                <input type="hidden" name="id" id="edit_id">
                <input name="judul" id="edit_judul" required>
                <input name="target_nominal" id="edit_nominal" type="number" required>
                
                <label>Update Tanggal Target</label>
                <input type="date" name="target_tanggal" id="edit_tanggal" required>
                
                <button name="update">Update Data</button>
                <button type="button" class="btn-close" onclick="closeModal()">Batal</button>
            </form>
        </div>
    </div>

    <script>
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('input_tgl_tambah').setAttribute('min', today);
        document.getElementById('edit_tanggal').setAttribute('min', today);

        function openTambah(){ document.getElementById("modalTambah").style.display="flex"; }
        
        function openEdit(id,judul,nominal,tanggal){
            document.getElementById("modalEdit").style.display="flex";
            document.getElementById("edit_id").value=id;
            document.getElementById("edit_judul").value=judul;
            document.getElementById("edit_nominal").value=nominal;
            document.getElementById("edit_tanggal").value=tanggal;
        }

        function closeModal(){
            document.getElementById("modalTambah").style.display="none";
            document.getElementById("modalEdit").style.display="none";
        }
    </script>
</body>
</html>