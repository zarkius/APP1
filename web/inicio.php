/**
 * This script handles Google OAuth 2.0 authentication and user management for a PHP application.
 * 
 * Key functionalities:
 * - Configures the Google OAuth 2.0 client with credentials, redirect URI, and required scopes.
 * - Establishes a connection to a MySQL database for storing user information and tokens.
 * - Manages the OAuth flow, including handling authorization codes and exchanging them for access tokens.
 * - Saves user information and tokens in the database, updating them if the user already exists.
 * - Verifies if the user is already authenticated and handles token expiration by refreshing tokens.
 * - Displays user information if authenticated or provides a link to initiate the Google login process.
 * 
 * Main sections:
 * 1. OAuth Client Configuration:
 *    - Sets up the Google_Client with credentials, redirect URI, and scopes for email and profile access.
 * 
 * 2. Database Connection:
 *    - Connects to a MySQL database to store and retrieve user data and tokens.
 * 
 * 3. OAuth Flow Handling:
 *    - Handles the exchange of authorization codes for access tokens.
 *    - Saves user information (Google ID, name, email) and tokens (access token, refresh token, expiry) in the database.
 *    - Updates existing user records if the user already exists.
 * 
 * 4. Token Management:
 *    - Checks if the user is already authenticated by verifying the session.
 *    - Handles token expiration by refreshing the token using the stored refresh token.
 *    - Updates the database with the new access token and expiry time.
 * 
 * 5. User Information Display:
 *    - Retrieves and displays the authenticated user's name and email.
 *    - Provides a logout link for the user to end the session.
 * 
 * 6. Login Link:
 *    - Generates and displays a Google login URL for users who are not authenticated.
 * 
 * Error Handling:
 * - Handles database connection errors and displays appropriate error messages.
 * - Handles token retrieval errors and displays error messages if the OAuth flow fails.
 * 
 * Dependencies:
 * - Google API PHP Client Library (autoloaded via Composer).
 * - MySQL database for storing user data and tokens.
 * 
 * Note:
 * - Ensure that the `credentials.json` file is correctly configured with your Google API credentials.
 * - Update the redirect URI to match your application's URL.
 * - Secure sensitive data such as database credentials and tokens.
 */
<?php
session_start([
    'cookie_lifetime' => 86400, // 1 día
    'cookie_secure' => true,   // Solo enviar cookies en conexiones HTTPS
    'cookie_httponly' => true, // Evitar acceso a cookies desde JavaScript
]);

require_once __DIR__ . '/../vendor/autoload.php';

// Configuración del cliente OAuth 2.0
$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setRedirectUri('http://yposteriormente.com/google-api-php-client/web/inicio.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
$client->setAccessType('offline'); // Permite obtener un refresh_token
$client->setApprovalPrompt('force'); // Fuerza la pantalla de consentimiento

// Conexión a la base de datos
try {
    $mysqli = new mysqli('localhost', 'root', '', 'app1');
    if ($mysqli->connect_error) {
        throw new Exception('Error de conexión a la base de datos: ' . $mysqli->connect_error);
    }
} catch (Exception $e) {
    logError($e->getMessage());
    exit('Error interno. Por favor, intente más tarde.');
}

function guardarUsuario($mysqli, $googleId, $nombre, $email, $accessToken, $refreshToken, $tokenExpiry) {
    $stmt = $mysqli->prepare("INSERT INTO usuarios (google_id, nombre, email, access_token, refresh_token, token_expiry)
                              VALUES (?, ?, ?, ?, ?, ?)
                              ON DUPLICATE KEY UPDATE 
                                  nombre = VALUES(nombre), 
                                  email = VALUES(email), 
                                  access_token = VALUES(access_token), 
                                  refresh_token = IF(VALUES(refresh_token) != '', VALUES(refresh_token), refresh_token), 
                                  token_expiry = VALUES(token_expiry)");
    $stmt->bind_param('ssssss', $googleId, $nombre, $email, $accessToken, $refreshToken, $tokenExpiry);
    return $stmt->execute();
}

function logError($message) {
    error_log($message, 3, __DIR__ . '/error.log');
}

function redirigir($url) {
    header("Location: $url");
    exit();
}

// Manejo del flujo OAuth
if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (isset($token['error'])) {
            throw new Exception('Error al obtener el token: ' . $token['error']);
        }

        $_SESSION['access_token'] = $token;
        $client->setAccessToken($token);

        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        if (empty($userInfo->id) || empty($userInfo->email)) {
            throw new Exception('Información del usuario incompleta.');
        }

        $googleId = $userInfo->id;
        $nombre = $userInfo->name;
        $email = $userInfo->email;
        $accessToken = $token['access_token'];
        $refreshToken = $token['refresh_token'] ?? null;
        $tokenExpiry = date('Y-m-d H:i:s', time() + $token['expires_in']);

        guardarUsuario($mysqli, $googleId, $nombre, $email, $accessToken, $refreshToken, $tokenExpiry);
        redirigir('inicio.php');
    } catch (Exception $e) {
        logError($e->getMessage());
        exit('Error durante la autenticación.');
    }
}

// Verificar si el usuario ya está autenticado
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);

    // Verificar si el token ha expirado
    if ($client->isAccessTokenExpired()) {
        // Renovar el token usando el refresh_token
        $query = "SELECT refresh_token FROM usuarios LIMIT 1";
        $result = $mysqli->query($query);
        if ($result && $row = $result->fetch_assoc()) {
            $refreshToken = $row['refresh_token'];
            if (!$client->fetchAccessTokenWithRefreshToken($refreshToken)) {
                unset($_SESSION['access_token']);
                redirigir('inicio.php');
            }

            // Actualizar el token en la base de datos
            $newAccessToken = $client->getAccessToken()['access_token'];
            $newTokenExpiry = date('Y-m-d H:i:s', time() + $client->getAccessToken()['expires_in']);
            $updateQuery = "UPDATE usuarios SET access_token='$newAccessToken', token_expiry='$newTokenExpiry' WHERE refresh_token='$refreshToken'";
            $mysqli->query($updateQuery);
        } else {
            unset($_SESSION['access_token']);
            redirigir('inicio.php');
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