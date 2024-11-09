<?php
session_start();
include('../includes/db.php');

// Cek jika pengguna adalah dokter
if ($_SESSION['role'] !== 'Dokter') {
    header("Location: ../index.php");
    exit();
}

// Ambil data konsultasi dari database
$query_konsultasi = "SELECT * FROM konsultasi";
$result_konsultasi = $conn->query($query_konsultasi);

// Ambil data pasien dari database
$query_pasien = "SELECT id_pasien, nama FROM pasien"; // Hanya ambil id_pasien dan nama
$result_pasien = $conn->query($query_pasien);

// Proses input data konsultasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_konsultasi = $_POST['id_konsultasi'];
    $tanggal = $_POST['tanggal'];
    $id_pasien = $_POST['id_pasien'];
    $hasil_analisis = $_POST['hasil_analisis'];
    $resep_obat = $_POST['resep_obat'];

    // Query untuk memasukkan data konsultasi
    $insert_query = "INSERT INTO konsultasi (id_konsultasi, tanggal, id_pasien, hasil_analisis, resep_obat) 
    VALUES ('$id_konsultasi', '$tanggal', '$id_pasien', '$hasil_analisis', '$resep_obat')";

    if ($conn->query($insert_query) === TRUE) {
        echo "<script>alert('Data konsultasi berhasil disimpan!');</script>";
        echo "<script>window.location = 'dokter_dashboard.php';</script>";
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
    <title>Dashboard Dokter</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard Dokter</h2>
        <form action="logout.php" method="post" style="text-align: right;">
            <input type="submit" value="Logout">
        </form><br>
        <!-- Form Input Data Konsultasi -->
        <h3>Input Data Konsultasi</h3>
        <form method="POST" action="dokter_dashboard.php">
            <label for="id_konsultasi">ID Konsultasi:</label>
            <input type="text" id="id_konsultasi" name="id_konsultasi" required>

            <label for="tanggal">Tanggal (DDMMYY):</label>
            <input type="text" id="tanggal" name="tanggal" required placeholder="DDMMYY">

            <label for="id_pasien">ID Pasien:</label>
            <select id="id_pasien" name="id_pasien" required>
                <option value="">-- Pilih Pasien --</option>
                <?php while ($row = $result_pasien->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_pasien']; ?>">
                        <?php echo $row['id_pasien'] . " - " . $row['nama']; ?>
                    </option>
                <?php } ?>
            </select> <br>

            <br><label for="hasil_analisis">Hasil Analisa Dokter:</label>
            <textarea id="hasil_analisis" name="hasil_analisis" required></textarea>

            <label for="resep_obat">Resep Obat:</label>
            <textarea id="resep_obat" name="resep_obat" required></textarea> <br>

            <input type="submit" value="Simpan Data Konsultasi">
        </form>

        <!-- Tabel Data Konsultasi -->
        <h3>Data Konsultasi</h3>
        <table border="1">
            <tr>
                <th>ID Konsultasi</th>
                <th>Tanggal</th>
                <th>ID Pasien</th>
                <th>Hasil Analisa</th>
                <th>Resep Obat</th>
            </tr>
            <?php while ($row = $result_konsultasi->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id_konsultasi']; ?></td>
                <td><?php echo $row['tanggal']; ?></td>
                <td><?php echo $row['id_pasien']; ?></td>
                <td><?php echo $row['hasil_analisis']; ?></td>
                <td><?php echo $row['resep_obat']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
