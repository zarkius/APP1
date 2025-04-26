<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();



require_once __DIR__ . '/testdb.php'; // Asegúrate de que este archivo existe y contiene la conexión a la base de datos
date_default_timezone_set('UTC');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$nonce = base64_encode(random_bytes(16)); // Generar un nonce único
header("Content-Security-Policy: script-src 'self' https://accounts.google.com https://www.gstatic.com https://pagead2.googlesyndication.com https://fundingchoicesmessages.google.com https://cdnjs.cloudflare.com https://www.googletagmanager.com 'unsafe-inline'; object-src 'none';");
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
    <title>Aula Virtual | Home</title>
  </head>
  <body>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-3JVV0PF8GG"></script>
<script nonce="<?php echo $nonce; ?>">
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-3JVV0PF8GG');
</script>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1801340051704618"
  crossorigin="anonymous"></script>
    <header style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background-color: #f4f4f4; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
      <div style="flex: 1;">
      <img src="assets/img/banner.jpeg" alt="Banner de la página" style="width: 100%; height: 300px;" />
      </div>
      <aside style="flex: 0 0 20%; padding: 10px; background-color: #f4f4f4; height: 280px; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">
      <nav>
        <ul style="list-style-type: none; padding: 0; margin: 0; text-align: center;">
        <li><a href="/index.php" style="text-decoration: none; color: #333;">Inicio</a></li>
        <li><a href="/jsPDF/index.php" style="text-decoration: none; color: #333;">Generar PDF</a></li>
        <li><a href="/phpmailer/index.php">Cuenta</a></li>
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
      <div id="consent-banner" style="position: fixed; bottom: 0; left: 0; width: 100%; background-color: #f4f4f4; box-shadow: 0 -2px 5px rgba(0,0,0,0.1); padding: 10px; text-align: center; display: none;">
        <p style="margin: 0; font-size: 14px;">
          Este sitio utiliza cookies para mejorar la experiencia del usuario. Al continuar navegando, aceptas nuestro uso de cookies. 
          <a href="/privacidad.php" style="text-decoration: none; color: #4285F4;">Más información</a>.
        </p>
        <button id="accept-cookies" style="background-color: #4285F4; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; margin-top: 5px;">
          Aceptar
        </button>
      </div>
      <script nonce="<?php echo $nonce; ?>">
        document.addEventListener('DOMContentLoaded', function () {
          const consentBanner = document.getElementById('consent-banner');
          const acceptButton = document.getElementById('accept-cookies');
          const consentGiven = localStorage.getItem('cookiesAccepted');

          if (!consentGiven) {
            consentBanner.style.display = 'block';
          }

          acceptButton.addEventListener('click', function () {
            localStorage.setItem('cookiesAccepted', 'true');
            consentBanner.style.display = 'none';
          });
        });
      </script>
        <!-- Contenido dinámico Principal -->
      <h2 style="text-align: center">Nuestros cursos:</h2>
<?php
      // Conectar a la base de datos
      $servername = $_ENV['DB_HOST'];
      $username = $_ENV['DB_USER'];
      $password = $_ENV['DB_PASSWORD'];
      $dbname = $_ENV['DB_NAME'];
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Verificar la conexión
      if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
      }

      // Consultar todos los cursos
      $sql = "SELECT id, autor, texto, enlaces FROM cursos";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        echo '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 20px;">';
        while ($row = $result->fetch_assoc()) {
          echo '<div style="background-color: #f9f9f9; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">';
          echo '<h3>' . htmlspecialchars($row["autor"]) . '</h3>';
          echo '<p>' . htmlspecialchars($row["texto"]) . '</p>';
        echo '<p><strong>Enlaces:</strong></p>';

        $nombre = htmlspecialchars($row["enlaces"]);
        echo '<a href="' . $nombre . '">' . htmlspecialchars($row["enlaces"]) . '</a>';
        echo '</div>';
        }
        echo '</div>';
      } else {
        echo '<p style="text-align: center;">No hay cursos disponibles.</p>';
      }

      $conn->close();
      ?>
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
