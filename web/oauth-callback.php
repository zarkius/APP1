<?php
require_once '../vendor/autoload.php';

session_start();

// Crea una instancia del cliente de Google
$client = new Google\Client();
$client->setAuthConfig('C:/xampp/htdocs/google-api-php-client/web/credentials.json');
$client->setRedirectUri('http://localhost/google-api-php-client/web/inicio.php');
$client->addScope(Google\Service\Drive::DRIVE_METADATA_READONLY);

// Verifica si se recibió el parámetro 'code'
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        echo 'Error al obtener el token: ' . htmlspecialchars($token['error']);
        exit;
    }

    // Guarda el token en la sesión
    $_SESSION['access_token'] = $token;

    // Redirige al inicio o a otra página
    header('Location: inicio.php');
    exit;
} else {
    echo 'No se recibió el código de autorización.';
}
?>