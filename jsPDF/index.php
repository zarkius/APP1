<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generar PDF</title>
    <?php
    header("Content-Security-Policy: script-src 'self' https://accounts.google.com https://www.gstatic.com https://pagead2.googlesyndication.com https://fundingchoicesmessages.google.com https://cdnjs.cloudflare.com https://www.googletagmanager.com 'unsafe-inline'; object-src 'none';");
    ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
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
  <header>
    <h1>Generar PDF desde un formulario</h1>
  </header>
  <main>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-3JVV0PF8GG"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-3JVV0PF8GG');
</script>
    <!-- Formulario para ingresar datos -->
    <form id="data-form">
      <label for="name"><h3>Pega el texto que desees convertir a <strong>PDF</strong></h3></label><br>
      <textarea id="name" name="name" placeholder="Ingresa texto" required style="width: 800px; height: 600px;"></textarea>
      <br><br>
      <button type="button" id="generate-pdf">Generar PDF</button>
    </form>

    <!-- Contenido dinámico que se actualizará -->
    <div id="content" style="display: none; white-space: pre-wrap;">
      <h2 id="dynamic-name"></h2>
    </div>
  </main>

  <script>
    document.getElementById('generate-pdf').addEventListener('click', () => {
      const { jsPDF } = window.jspdf;

      // Capturar los valores del formulario
      const name = document.getElementById('name').value;

      // Actualizar el contenido dinámico y preservar los saltos de línea
      document.getElementById('dynamic-name').textContent = name;
      document.getElementById('content').style.display = 'block';

      // Generar el PDF
      const pdf = new jsPDF();
      const content = document.getElementById('content'); // Seleccionar el contenido dinámico

      html2canvas(content).then((canvas) => {
        const imgData = canvas.toDataURL('image/png');
        const imgWidth = 190;
        const pageHeight = 295;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        const position = 10;

        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        pdf.save('formulario.pdf');
      });
    });
  </script>
</body>
</html>