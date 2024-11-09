<?php
session_start();
include('../includes/db.php');

// Cek jika pengguna adalah admin
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pasien = $_POST['id_pasien'];

    // Hapus data pasien dari database
    $query = "DELETE FROM pasien WHERE id_pasien = '$id_pasien'";

    if ($conn->query($query) === TRUE) {
        header("Location: admin_dashboard.php?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
