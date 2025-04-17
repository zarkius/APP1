<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generar PDF</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
  <header>
    <h1>Generar PDF desde un formulario</h1>
  </header>
  <main>
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