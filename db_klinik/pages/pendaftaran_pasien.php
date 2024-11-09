<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $status_keterangan = $_POST['status_keterangan'];

    // Query untuk memasukkan data pasien baru
    $query = "INSERT INTO pasien (nik, nama, tempat_lahir, tanggal_lahir, alamat, no_hp, status_keterangan) VALUES ('$nik', '$nama', '$tempat_lahir', '$tanggal_lahir', '$alamat', '$no_hp', '$status_keterangan')";

    if ($conn->query($query) === TRUE) {
        // Redirect ke halaman index jika berhasil
        header("Location: ../index.php?success=1");
        exit();
    } else {
        $error = "Terjadi kesalahan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pasien</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Pendaftaran Pasien Baru</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form action="pendaftaran_pasien.php" method="POST">
            <div class="form-group">
                <label for="nik">NIK:</label>
                <input type="text" id="nik" name="nik" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir:</label>
                <input type="text" id="tempat_lahir" name="tempat_lahir" required>
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea id="alamat" name="alamat" required></textarea>
            </div>
            <div class="form-group">
                <label for="no_hp">No HP:</label>
                <input type="text" id="no_hp" name="no_hp" required>
            </div>
            <div class="form-group">
                <label for="status_keterangan">Status Keterangan:</label>
                <select id="status_keterangan" name="status_keterangan" required>
                    <option value="Dosen">Dosen</option>
                    <option value="Karyawan">Karyawan</option>
                    <option value="Mahasiswa">Mahasiswa</option>
                    <option value="Umum">Umum</option>
                </select>
            </div>
            <input type="submit" value="Daftar">
        </form>
    </div>
</body>
</html>
