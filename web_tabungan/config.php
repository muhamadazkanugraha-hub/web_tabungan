<?php
$koneksi = mysqli_connect("localhost", "root", "", "tabungan_db");
if (!$koneksi) {
    die("Koneksi gagal");
}
?>