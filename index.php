<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php
    header("Content-Security-Policy: script-src 'self' https://www.gstatic.com https://accounts.google.com; object-src 'none';");
    ?>
    <meta
      name="google-signin-client_id"
      content="483619470669-mj5uaa1j7mh0url8molc7nnv846cli2u.apps.googleusercontent.com"
    />
    <title>Inicio de Sesión con Google</title>
  </head>
  <body>
    <header>
      <h1>Inicio de Sesión con Google</h1>
    </header>
    <main>
      <!-- Script de Google Identity Services -->
       
      <script src="https://apis.google.com/js/platform.js" async defer></script>
      <div class="g-signin2" data-onsuccess="onSignIn"></div>
      <script>
        function onSignIn(googleUser) {
          var profile = googleUser.getBasicProfile();
          console.log("ID: " + profile.getId()); // Do not send to your backend! Use an ID token instead.
          console.log("Name: " + profile.getName());
          console.log("Image URL: " + profile.getImageUrl());
          console.log("Email: " + profile.getEmail()); // This is null if the 'email' scope is not present.
        }
      </script>
    </main>
    <footer>
      <p>© 2025 Ejemplo de Inicio de Sesión con Google</p>
    </footer>
  </body>
</html>
