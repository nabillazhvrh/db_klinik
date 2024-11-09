<?php
include('../includes/db.php');

// Ambil data pembayaran dari database
$query_pembayaran = "
    SELECT pembayaran.id_pembayaran, 
           pembayaran.id_konsultasi, 
           pembayaran.jumlah_bayar, 
           konsultasi.id_pasien, 
           pasien.nama, 
           pasien.status_keterangan, 
           pembayaran.status_bayar 
    FROM pembayaran 
    JOIN konsultasi ON pembayaran.id_konsultasi = konsultasi.id_konsultasi 
    JOIN pasien ON konsultasi.id_pasien = pasien.id_pasien";
$result_pembayaran = $conn->query($query_pembayaran);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Laporan Pembayaran</h2>

        <table>
            <tr>
                <th>ID Pembayaran</th>
                <th>ID Konsultasi</th>
                <th>Jumlah Bayar</th>
                <th>Nama Pasien</th>
                <th>Status Pasien</th>
                <th>Status Bayar</th>
            </tr>
            <?php while ($row = $result_pembayaran->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id_pembayaran']; ?></td>
                <td><?php echo $row['id_konsultasi']; ?></td>
                <td><?php echo number_format($row['jumlah_bayar'], 2, ',', '.'); ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['status_keterangan']; ?></td>
                <td><?php echo $row['status_bayar']; ?></td>
            </tr>
            <?php } ?>
        </table>

        <script>
            window.print(); // Otomatis memunculkan dialog cetak ketika halaman dibuka
        </script><br>
        <!-- Tombol Kembali -->
        <a href="kasir_dashboard.php" class="back-button">Kembali ke Dashboard Kasir</a>
    </div>
</body>
</html>
