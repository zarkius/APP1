<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$user = $_SESSION['user'];
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
        <h1>Bienvenido, <?php echo htmlspecialchars($user['name']); ?></h1>
    </header>
    <main>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <img src="<?php echo htmlspecialchars($user['picture']); ?>" alt="Foto de perfil">
        <p><a href="logout.php">Cerrar sesión</a></p>
    </main>
</body>
</html>