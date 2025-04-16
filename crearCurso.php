<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    // Redirigir al usuario a la página de inicio de sesión
    header('Location: index.php');
    exit();
}

// Código para usuarios autenticados
echo "Bienvenido, estás autenticado.";
?>