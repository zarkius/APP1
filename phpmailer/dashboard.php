<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
require_once 'conn.php';
session_start();

$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $_SESSION['temp_user'] = [
        'id' => $user_data['id'],
        'otp' => $user_data['otp'],
        'otp_expiry' => $user_data['otp_expiry']
    ];
} else {
    echo "<script>alert('No user found with the provided email.');</script>";
    header("Location: index.php");
    exit();
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to the Dashboard, <?php echo $email; ?>!</h1>

    <a href="../negocio.php">Crear página para tu negocio</a>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
