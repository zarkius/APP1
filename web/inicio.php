<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Configuración del cliente OAuth 2.0
$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setRedirectUri('http://yposteriormente.com/google-api-php-client/web/inicio.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$client->setAccessType('offline'); // Permite obtener un refresh_token
$client->setApprovalPrompt('force'); // Fuerza la pantalla de consentimiento

// Conexión a la base de datos
$mysqli = new mysqli('localhost', 'root', '', 'app1');
if ($mysqli->connect_error) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}

// Manejo del flujo OAuth
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        echo 'Error al obtener el token: ' . htmlspecialchars($token['error']);
        exit();
    }

    // Guardar tokens en la sesión
    $_SESSION['access_token'] = $token;

    // Obtener información del usuario
    $client->setAccessToken($token);
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Guardar la información del usuario y los tokens en la base de datos
    $googleId = $mysqli->real_escape_string($userInfo->id);
    $nombre = $mysqli->real_escape_string($userInfo->name);
    $email = $mysqli->real_escape_string($userInfo->email);
    $accessToken = $mysqli->real_escape_string($token['access_token']);
    $refreshToken = isset($token['refresh_token']) ? $mysqli->real_escape_string($token['refresh_token']) : null;
    $tokenExpiry = date('Y-m-d H:i:s', time() + $token['expires_in']);

    $query = "INSERT INTO usuarios (google_id, nombre, email, access_token, refresh_token, token_expiry)
              VALUES ('$googleId', '$nombre', '$email', '$accessToken', '$refreshToken', '$tokenExpiry')
              ON DUPLICATE KEY UPDATE 
                  nombre='$nombre', 
                  email='$email', 
                  access_token='$accessToken', 
                  refresh_token=IF('$refreshToken' != '', '$refreshToken', refresh_token), 
                  token_expiry='$tokenExpiry'";
    if ($mysqli->query($query) === TRUE) {
        header('Location: inicio.php');
        exit();
    } else {
        echo 'Error al guardar los datos del usuario: ' . $mysqli->error;
    }
}

// Verificar si el usuario ya está autenticado
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Verificar si el token ha expirado
    if ($client->isAccessTokenExpired()) {
        // Renovar el token usando el refresh_token
        $query = "SELECT refresh_token FROM usuarios WHERE google_id = (SELECT google_id FROM usuarios LIMIT 1)";
        $result = $mysqli->query($query);
        if ($result && $row = $result->fetch_assoc()) {
            $refreshToken = $row['refresh_token'];
            $client->fetchAccessTokenWithRefreshToken($refreshToken);

            // Actualizar el token en la base de datos
            $newAccessToken = $client->getAccessToken()['access_token'];
            $newTokenExpiry = date('Y-m-d H:i:s', time() + $client->getAccessToken()['expires_in']);
            $updateQuery = "UPDATE usuarios SET access_token='$newAccessToken', token_expiry='$newTokenExpiry' WHERE refresh_token='$refreshToken'";
            $mysqli->query($updateQuery);
        } else {
            unset($_SESSION['access_token']);
            header('Location: inicio.php');
            exit();
        }
    }

    // Obtener información del usuario
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    echo '<h1>Bienvenido, ' . htmlspecialchars($userInfo->name) . '</h1>';
    echo '<p>Email: ' . htmlspecialchars($userInfo->email) . '</p>';
    echo '<a href="logout.php">Cerrar sesión</a>';
} else {
    // Generar la URL de autenticación
    $authUrl = $client->createAuthUrl();
    echo '<a href="' . htmlspecialchars($authUrl) . '">Iniciar sesión con Google</a>';
}
?>