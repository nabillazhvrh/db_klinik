<?php
session_start();
include('includes/db.php');

// Check if user is already logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Admin') {
        header("Location: pages/admin_dashboard.php");
    } elseif ($_SESSION['role'] === 'Kasir') {
        header("Location: pages/kasir_dashboard.php");
    } elseif ($_SESSION['role'] === 'Dokter') {
        header("Location: pages/dokter_dashboard.php");
    }
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'Admin') {
            header("Location: pages/admin_dashboard.php");
        } elseif ($user['role'] === 'Kasir') {
            header("Location: pages/kasir_dashboard.php");
        } elseif ($user['role'] === 'Dokter') {
            header("Location: pages/dokter_dashboard.php");
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Login">
        </form> <br>

        <!-- Tombol Pendaftaran Pasien -->
        <form action="pages/pendaftaran_pasien.php" method="get">
            <input type="submit" value="Pendaftaran Pasien Baru">
        </form>
    </div>
</body>
</html>

