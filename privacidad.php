<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            padding: 0;
        }
        h1, h2 {
            color: #333;
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
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
    <h1>Política de Privacidad</h1>
    <p>En <strong>Aula Virtual</strong>, valoramos su privacidad y nos comprometemos a proteger la información personal que comparte con nosotros. Esta política describe cómo recopilamos, usamos y protegemos su información.</p>

    <h2>Información que recopilamos</h2>
    <p>Podemos recopilar la siguiente información:</p>
    <ul>
        <li>Nombre y datos de contacto, como dirección de correo electrónico y número de teléfono.</li>
        <li>Información demográfica, como código postal, preferencias e intereses.</li>
        <li>Otra información relevante para encuestas y/o ofertas.</li>
    </ul>

    <h2>Uso de la información</h2>
    <p>Utilizamos la información recopilada para:</p>
    <ul>
        <li>Proveer nuestros servicios y productos.</li>
        <li>Mejorar nuestros productos y servicios.</li>
        <li>Enviar correos electrónicos promocionales sobre nuevos productos, ofertas especiales u otra información que consideremos interesante.</li>
    </ul>

    <h2>Seguridad</h2>
    <p>Estamos comprometidos a garantizar que su información esté segura. Para prevenir el acceso no autorizado, implementamos procedimientos físicos, electrónicos y administrativos adecuados.</p>

    <h2>Cookies</h2>
    <p>Este sitio web puede utilizar cookies para mejorar la experiencia del usuario. Puede optar por aceptar o rechazar cookies en la configuración de su navegador.</p>

    <h2>Enlaces a otros sitios web</h2>
    <p>Nuestro sitio web puede contener enlaces a otros sitios de interés. No somos responsables de la protección y privacidad de la información que proporcione al visitar dichos sitios.</p>

    <h2>Control de su información personal</h2>
    <p>Puede restringir la recopilación o el uso de su información personal en cualquier momento poniéndose en contacto con nosotros a través de los medios proporcionados en este sitio web.</p>

    <p>Última actualización: <?php echo date("d/m/Y"); ?></p>
</body>
</html>