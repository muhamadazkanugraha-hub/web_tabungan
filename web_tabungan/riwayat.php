<?php
include "config.php";
$data=mysqli_query($koneksi,"SELECT * FROM tabungan WHERE status=1");
?>

<div class="sidebar">
    <h2> Tabungan</h2>
    <a href="home.php">Home</a>
    <a href="riwayat.php">Riwayat</a>
</div>

<div class="content">
<h2>Riwayat Tercapai</h2>
<?php while($t=mysqli_fetch_assoc($data)){ ?>
<div class="card">
    <h3><?= $t['judul'] ?></h3>
    <p>Target tercapai</p>
</div>
<?php } ?>
</div>