<?php
require_once __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('UTC');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet" />
    <style>
      body {
        font-family: 'Ubuntu', sans-serif;
      }
    </style>
    <title>Inicio de Sesión con Google</title>
    <?php
    // Configuración de la política de seguridad de contenido (CSP)
    header("Content-Security-Policy: script-src 'self' https://accounts.google.com https://www.gstatic.com; object-src 'none';");
    ?>
  </head>
  <body>
    <header style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background-color: #f4f4f4; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
      <div style="flex: 1;">
      <img src="assets/img/banner.jpeg" alt="Banner de la página" style="width: 100%; height: 300px;" />
      </div>
      <aside style="flex: 0 0 20%; padding: 10px; background-color: #f4f4f4; height: 280px; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">
      <nav>
        <ul style="list-style-type: none; padding: 0; margin: 0; text-align: center;">
        <li><a href="#" style="text-decoration: none; color: #333;">Inicio</a></li>
        <li><a href="crearCurso.php" style="text-decoration: none; color: #333;">Crear Curso</a></li>
        <br>
        <li>
          <a href="<?php echo htmlspecialchars($authUrl); ?>" style="text-decoration: none;">
          <button style="background-color: #4285F4; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            Iniciar sesión con Google
          </button>
          </a>
        </li>
        </ul>
      </nav>
      </aside>
    </header>
    <main>
        <!-- Contenido dinámico Principal -->
      <h2 style="text-align: center">Nuestros cursos:</h2>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 20px;">
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 1</h3>
          <p>Contenido de la primera vista.</p>
        </div>
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 2</h3>
          <p>Contenido de la segunda vista.</p>
        </div>
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 3</h3>
          <p>Contenido de la tercera vista.</p>
        </div>
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 4</h3>
          <p>Contenido de la tercera vista.</p>
        </div>
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 5</h3>
          <p>Contenido de la tercera vista.</p>
        </div>
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 6</h3>
          <p>Contenido de la tercera vista.</p>
        </div>
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 7</h3>
          <p>Contenido de la tercera vista.</p>
        </div>
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 8</h3>
          <p>Contenido de la tercera vista.</p>
        </div>
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
          <h3>Vista 9</h3>
          <p>Contenido de la tercera vista.</p>
        </div>
      </div>
    </div>
    </main>
    <footer>
    <p style="text-align: center; padding: 10px; background-color: #f4f4f4; margin: 0;">
      &copy; <?php echo date("Y-m-d"); ?> Aula Virtual. Todos los derechos reservados. | <a href="/privacidad.php">Privacidad</a> | <a href="#">Contacto</a>

    </p>
    </footer>
  </body>
</html>
