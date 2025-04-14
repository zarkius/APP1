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
      <!-- Configuración del botón de inicio de sesión de Google -->
      <div id="g_id_onload"
           data-client_id="483619470669-mj5uaa1j7mh0url8molc7nnv846cli2u.apps.googleusercontent.com"
           data-callback="handleCredentialResponse"
           data-auto_prompt="false">
      </div>
      <div class="g_id_signin"
           data-type="standard"
           data-shape="rectangular"
           data-theme="outline"
           data-text="sign_in_with"
           data-size="large"
           data-logo_alignment="left">
      </div>

      <!-- Script de Google Identity Services -->
      <script src="https://accounts.google.com/gsi/client" async defer></script>
      <script>
        // Callback para manejar la respuesta del inicio de sesión
        function handleCredentialResponse(response) {
          console.log("Token ID codificado (JWT): " + response.credential);

          // Enviar el token al servidor para validación
          fetch('validate_token.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ credential: response.credential }),
          })
          .then((res) => res.json())
          .then((data) => {
            console.log("Respuesta del servidor:", data);
            if (data.status === 'success') {
              alert("Inicio de sesión exitoso. Bienvenido, " + data.name);
            } else {
              alert("Error en la autenticación.");
            }
          })
          .catch((error) => {
            console.error("Error al enviar el token al servidor:", error);
          });
        }

        // Inicializar el cliente de Google
        window.onload = function () {
          google.accounts.id.initialize({
            client_id: "483619470669-mj5uaa1j7mh0url8molc7nnv846cli2u.apps.googleusercontent.com",
            callback: handleCredentialResponse,
          });
          google.accounts.id.prompt(); // Mostrar el prompt de inicio de sesión
        };
      </script>
    </main>
    <footer>
      <p>© 2025 Ejemplo de Inicio de Sesión con Google</p>
    </footer>
  </body>
</html>
