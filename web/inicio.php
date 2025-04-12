<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Configuración del cliente OAuth 2.0
$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setRedirectUri('http://localhost/google-api-php-client/web/inicio.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

// Manejo del flujo OAuth
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    header('Location: inicio.php');
    exit();
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    echo '<h1>Bienvenido, ' . htmlspecialchars($userInfo->name) . '</h1>';
    echo '<p>Email: ' . htmlspecialchars($userInfo->email) . '</p>';
    echo '<a href="logout.php">Cerrar sesión</a>';
} else {
    $authUrl = $client->createAuthUrl();
    echo '<a href="' . htmlspecialchars($authUrl) . '">Iniciar sesión con Google</a>';
}
?>
<?php
require_once 'vendor/autoload.php';

session_start();

if (!isset($_SESSION['access_token'])) {
    echo 'No estás autenticado. Por favor, inicia sesión.';
    exit;
}

$client = new Google\Client();
$client->setAccessToken($_SESSION['access_token']);

if ($client->isAccessTokenExpired()) {
    echo 'El token ha expirado. Por favor, vuelve a iniciar sesión.';
    exit;
}

echo '¡Autenticación exitosa!';
?>