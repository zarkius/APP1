<?php
require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('UTC');
session_start();

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->setRedirectUri('https://yposteriormente.com/callback.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

$authUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio de Sesión con Google</title>
    <?php
    // Configuración de la política de seguridad de contenido (CSP)
    header("Content-Security-Policy: script-src 'self' https://accounts.google.com https://www.gstatic.com; object-src 'none';");
    ?>
  </head>
  <body>
    <header>
      <h1>Inicio de Sesión con Google</h1>
    </header>
    <main>
      <a href="<?php echo htmlspecialchars($authUrl); ?>">Iniciar sesión con Google</a>
    </main>
    <footer>
      <p>© 2025 Ejemplo de Inicio de Sesión con Google</p>
    </footer>
  </body>
</html>
