<?php
session_start();
include('../includes/db.php');

// Cek jika pengguna adalah admin
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

$id_pasien = $_GET['id_pasien'];
$query = "SELECT * FROM pasien WHERE id_pasien = '$id_pasien'";
$result = $conn->query($query);
$pasien = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $status_keterangan = $_POST['status_keterangan'];

    // Query untuk mengupdate data pasien
    $update_query = "UPDATE pasien SET nik='$nik', nama='$nama', tempat_lahir='$tempat_lahir', tanggal_lahir='$tanggal_lahir', alamat='$alamat', no_hp='$no_hp', status_keterangan='$status_keterangan' WHERE id_pasien='$id_pasien'";

    if ($conn->query($update_query) === TRUE) {
        header("Location: admin_dashboard.php?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pasien</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Data Pasien</h2>
        <form method="POST" action="edit_pasien.php?id_pasien=<?php echo $id_pasien; ?>">
            <label for="nik">NIK:</label>
            <input type="text" id="nik" name="nik" value="<?php echo $pasien['nik']; ?>" required>

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo $pasien['nama']; ?>" required>

            <label for="tempat_lahir">Tempat Lahir:</label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?php echo $pasien['tempat_lahir']; ?>" required>

            <label for="tanggal_lahir">Tanggal Lahir:</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $pasien['tanggal_lahir']; ?>" required>

            <label for="alamat">Alamat:</label>
            <textarea id="alamat" name="alamat" required><?php echo $pasien['alamat']; ?></textarea>

            <label for="no_hp">No HP:</label>
            <input type="text" id="no_hp" name="no_hp" value="<?php echo $pasien['no_hp']; ?>" required>

            <label for="status_keterangan">Status Keterangan:</label>
            <select id="status_keterangan" name="status_keterangan" required>
                <option value="Dosen" <?php if ($pasien['status_keterangan'] == 'Dosen') echo 'selected'; ?>>Dosen</option>
                <option value="Karyawan" <?php if ($pasien['status_keterangan'] == 'Karyawan') echo 'selected'; ?>>Karyawan</option>
                <option value="Mahasiswa" <?php if ($pasien['status_keterangan'] == 'Mahasiswa') echo 'selected'; ?>>Mahasiswa</option>
                <option value="Umum" <?php if ($pasien['status_keterangan'] == 'Umum') echo 'selected'; ?>>Umum</option>
            </select>

            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
