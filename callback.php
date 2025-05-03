<?php
require_once __DIR__ . '/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setRedirectUri('http://localhost:3000/callback.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        echo 'Error al obtener el token: ' . htmlspecialchars($token['error']);
        exit();
    }

    $client->setAccessToken($token);

    // Obtener información del usuario
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Guardar la información del usuario en la sesión
    $_SESSION['user'] = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture,
    ];

    // Aquí puedes guardar la información del usuario en tu base de datos si es necesario
    // Por ejemplo, puedes usar PDO para insertar o actualizar la información del usuario en tu base de datos
    // Redirigir al usuario a la página principal
    header('Location: profile.php');
    exit();
}
?>