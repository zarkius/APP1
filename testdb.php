<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Configuración de la base de datos
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

// Crear una conexión mysqli
$mysqli = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Error al conectar a la base de datos: " . $mysqli->connect_error);
}

//echo "Conexión exitosa a la base de datos.";
function cursos() {
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
}