<?php
session_start();
include('../includes/db.php');

// Cek jika pengguna adalah kasir
if ($_SESSION['role'] !== 'Kasir') {
    header("Location: ../index.php");
    exit();
}

// Ambil data konsultasi dari database
$query_konsultasi = "SELECT * FROM konsultasi";
$result_konsultasi = $conn->query($query_konsultasi);

// Periksa jika query berhasil
if (!$result_konsultasi) {
    echo "Error in query: " . $conn->error;
    exit();
}

// Proses input pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_konsultasi = $_POST['id_konsultasi'];
    $jumlah_bayar = 0;
    $status_bayar = ''; // Kosongkan status bayar

    // Ambil status pasien berdasarkan id_konsultasi dari tabel pasien
    $query_pasien = "
        SELECT pasien.status_keterangan 
        FROM konsultasi 
        JOIN pasien ON konsultasi.id_pasien = pasien.id_pasien 
        WHERE konsultasi.id_konsultasi = '$id_konsultasi'";
    
    $result_pasien = $conn->query($query_pasien);

    // Periksa jika query berhasil
    if ($result_pasien) {
        $pasien_data = $result_pasien->fetch_assoc();
        
        // Pastikan data pasien ditemukan
        if ($pasien_data) {
            $status_keterangan = $pasien_data['status_keterangan'];

            // Tentukan jumlah bayar dan status bayar berdasarkan status
            if ($status_keterangan == 'Dosen' || $status_keterangan == 'Karyawan') {
                // Jika pasien adalah Dosen atau Karyawan
                $jumlah_bayar = 0; // Tidak dikenakan biaya
                $status_bayar = 'Gratis'; // Status otomatis gratis
            } else {
                // Untuk Mahasiswa dan Umum
                $jumlah_bayar = 100000; // Misal biaya untuk Mahasiswa dan Umum
                $status_bayar = 'Dibayar'; // Status bayar harus dibayar
            }

            // Query untuk memasukkan data pembayaran
            $insert_query = "INSERT INTO pembayaran (id_konsultasi, jumlah_bayar, status_bayar) VALUES ('$id_konsultasi', '$jumlah_bayar', '$status_bayar')";

            if ($conn->query($insert_query) === TRUE) {
                echo "<script>alert('Pembayaran berhasil dilakukan!');</script>";
                echo "<script>window.location = 'kasir_dashboard.php';</script>";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Data pasien tidak ditemukan.";
        }
    } else {
        echo "Error: " . $conn->error; // Tampilkan kesalahan jika query gagal
        exit(); // Hentikan eksekusi
    }
}

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
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard Kasir</h2>
        <form action="logout.php" method="post" style="text-align: left;">
            <input type="submit" value="Logout">
        </form><br>

        <!-- Tombol Print Laporan -->
        <button onclick="window.open('print_laporan.php', '_blank')">Print Laporan</button>
        <!-- Form Pembayaran -->
        <h3>Form Pembayaran</h3>
        <form method="POST" action="kasir_dashboard.php">
            <label for="id_konsultasi">ID Konsultasi:</label>
            <select id="id_konsultasi" name="id_konsultasi" required>
                <option value="">-- Pilih Konsultasi --</option>
                <?php while ($row = $result_konsultasi->fetch_assoc()) { ?>
                    <option value="<?php echo $row['id_konsultasi']; ?>">
                        <?php echo $row['id_konsultasi'] . " - " . $row['id_pasien']; ?>
                    </option>
                <?php } ?>
            </select>

            <input type="submit" value="Proses Pembayaran">
        </form>

        <!-- Tabel Data Pembayaran -->
        <h3>Data Pembayaran</h3>
        <table border="1">
            <tr>
                <th>ID Pembayaran</th>
                <th>ID Konsultasi</th>
                <th>Nama Pasien</th>
                <th>Status Pasien</th>
                <th>Jumlah Bayar</th>
                <th>Status Bayar</th>
             
            </tr>
            <?php while ($row = $result_pembayaran->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id_pembayaran']; ?></td>
                <td><?php echo $row['id_konsultasi']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['status_keterangan']; ?></td>
                <td><?php echo number_format($row['jumlah_bayar'], 2, ',', '.'); ?></td>
                <td><?php echo $row['status_bayar']; ?></td>
                
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
