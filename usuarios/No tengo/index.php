<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de No tengo</title>
</head>
<body>
    <h1>Bienvenido, No tengo</h1>
    <p><a href='../../logout.php'>Cerrar sesión</a></p>
</body>
</html>