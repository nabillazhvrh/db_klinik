<?php
session_start();
include('../includes/db.php');

// Cek jika pengguna adalah admin
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

// Ambil data pasien dari database
$query = "SELECT * FROM pasien";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard Admin</h2>

        <!-- Tombol Logout -->
        <form action="logout.php" method="post" style="text-align: right;">
            <input type="submit" value="Logout">
        </form><br>

        <!-- Tombol Pendaftaran Pasien Baru -->
        <form action="pendaftaran_pasien.php" method="get">
            <input type="submit" value="Pendaftaran Pasien Baru">
        </form><br>

        <!-- Tombol Cek Data Pasien -->
        <form action="cek_data_pasien.php" method="get">
            <input type="submit" value="Cek Data Pasien">
        </form>

        <!-- Tabel Data Pasien -->
        <h3>Data Pasien</h3>
        <table border="1">
            <tr>
                <th>ID Pasien</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id_pasien']; ?></td>
                <td><?php echo $row['nik']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['tempat_lahir']; ?></td>
                <td><?php echo $row['tanggal_lahir']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td><?php echo $row['no_hp']; ?></td>
                <td><?php echo $row['status_keterangan']; ?></td>
                <td>
                    <form action="edit_pasien.php" method="get" style="display:inline;">
                        <input type="hidden" name="id_pasien" value="<?php echo $row['id_pasien']; ?>">
                        <input type="submit" value="Edit">
                    </form>
                    <form action="hapus_pasien.php" method="post" style="display:inline;">
                        <input type="hidden" name="id_pasien" value="<?php echo $row['id_pasien']; ?>">
                        <input type="submit" value="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus pasien ini?');">
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
