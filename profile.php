<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// profile.php
session_start();
date_default_timezone_set('UTC');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

include('testdb.php');

// Preparar y ejecutar la consulta
$stmt = $mysqli->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $_SESSION['user']['name'], $_SESSION['user']['email']);
$stmt->execute();

// Verificar si se afectaron filas
if ($stmt->affected_rows > 0) {
    echo "<br>" . "Usuario guardado o actualizado correctamente.";
} else {
    echo "<br>" . "No se realizaron cambios en la base de datos.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
</head>
<body>
    <header>
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h1>
    </header>
    <main>
        <p>Email: <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
        <img src="<?php echo htmlspecialchars($_SESSION['user']['picture'] ?? 'default.jpg'); ?>" alt="Foto de perfil">
        <a href="/crearCurso.php">Publica tu curso</a>
        <?php 
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        ?>
        <p><a href="logout.php">Cerrar sesión</a></p>
    </main>
</body>
</html>