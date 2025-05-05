<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
} else {
include_once 'funcionesVarias.php';
}

$email = $_SESSION['email'];
echo $email;
?>
<form method="POST" action="funcionesVarias.php?funcion=crearDirectorio">
    <input type="text" name="nombre" placeholder="Nombre del negocio" required>
    <input type="hidden" name="propietario" value="<?php echo htmlspecialchars($email); ?>">
    <textarea name="descripcion" placeholder="Descripción del negocio"></textarea>
    <input type="hidden" name="fecha_creacion" value="<?php echo date('Y-m-d H:i:s'); ?>">
    <button type="submit">Enviar</button>
</form>