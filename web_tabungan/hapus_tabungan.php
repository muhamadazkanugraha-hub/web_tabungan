<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$id_user = $_SESSION['user'];

$query_foto = mysqli_query($koneksi, "SELECT foto FROM tabungan WHERE id='$id' AND user_id='$id_user'");
$data = mysqli_fetch_assoc($query_foto);
$nama_foto = $data['foto'];

mysqli_query($koneksi, "DELETE FROM menabung WHERE tabungan_id='$id'");

$delete = mysqli_query($koneksi, "DELETE FROM tabungan WHERE id='$id' AND user_id='$id_user'");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Hapus Data</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f4f7fe; }
    </style>
</head>
<body>

<?php
if ($delete) {
    if (!empty($nama_foto) && file_exists("uploads/" . $nama_foto)) {
        unlink("uploads/" . $nama_foto);
    }
    
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Dihapus!',
            text: 'Data impian kamu sudah bersih dari sistem.',
            confirmButtonColor: '#0b0b3b'
        }).then((result) => {
            window.location.href = 'home.php';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Waduh Gagal!',
            text: 'Terjadi kesalahan saat mencoba menghapus data.',
            confirmButtonColor: '#0b0b3b'
        }).then((result) => {
            window.location.href = 'home.php';
        });
    </script>";
}
?>

</body>
</html>