<?php
session_start();
include('../includes/db.php');

if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

$search_result = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search = $_POST['search'];
    $query = "SELECT * FROM pasien WHERE nik = '$search' OR nama LIKE '%$search%'";
    $search_result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Data Pasien</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Cek Data Pasien</h2>
        <form method="POST" action="cek_data_pasien.php">
            <label for="search">Masukkan NIK atau Nama Pasien:</label>
            <input type="text" id="search" name="search" required>
            <input type="submit" value="Cari">
        </form>

        <?php if ($search_result && $search_result->num_rows > 0) { ?>
            <h3>Hasil Pencarian:</h3>
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
                </tr>
                <?php while ($row = $search_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id_pasien']; ?></td>
                    <td><?php echo $row['nik']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['tempat_lahir']; ?></td>
                    <td><?php echo $row['tanggal_lahir']; ?></td>
                    <td><?php echo $row['alamat']; ?></td>
                    <td><?php echo $row['no_hp']; ?></td>
                    <td><?php echo $row['status_keterangan']; ?></td>
                </tr>
                <?php } ?>
            </table>
        <?php } elseif ($search_result && $search_result->num_rows == 0) { ?>
            <p>Data tidak ditemukan.</p>
        <?php } ?>

        <!-- Tombol Kembali -->
        <form action="admin_dashboard.php" method="get" style="margin-top: 20px;">
            <input type="submit" value="Kembali ke Dashboard">
        </form>
    </div>
</body>
</html>
