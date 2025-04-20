<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = '';
$username = '';
$password = '';

try {
    // Crear una conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    // Manejar errores de conexión
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>