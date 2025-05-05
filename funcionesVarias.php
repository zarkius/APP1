<?php

function generarArchivoDesdePlantilla($nombre, $descripcion, $tipoPlantilla) {
    // Leer el archivo JSON con las plantillas
    $json = file_get_contents(__DIR__ . '/plantillas.json');
    $plantillas = json_decode($json, true);

    // Verificar si la plantilla existe
    if (isset($plantillas[$tipoPlantilla])) {
        $contenido = $plantillas[$tipoPlantilla]['contenido'];

        // Reemplazar los marcadores con datos dinámicos
        $contenido = str_replace(['{{nombre}}', '{{descripcion}}'], [$nombre, $descripcion], $contenido);

        // Crear el archivo dinámico
        $ruta = __DIR__ . '/negocio/' . $nombre . '/' . $tipoPlantilla . '.php';
        if (!is_dir(dirname($ruta))) {
            mkdir(dirname($ruta), 0755, true);
        }

        if (file_put_contents($ruta, $contenido) !== false) {
            return $contenido; // Return the generated content
        } else {
            throw new Exception("Error al crear el archivo '$tipoPlantilla.php'.");
        }
    } else {
        throw new Exception("La plantilla '$tipoPlantilla' no existe.");
    }
}

if (isset($_GET['funcion']) && $_GET['funcion'] === 'crearDirectorio') {
    if (isset($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
        $propietario = isset($_POST['propietario']) ? $_POST['propietario'] : 'Desconocido';
        $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : 'Sin descripción';
        crearDirectorio($nombre); // Llama a la función con el valor enviado
        // Crear un archivo index.php dentro del directorio del negocio
        $ruta = __DIR__ . '/negocio/' . $nombre;
        $archivoIndex = $ruta . '/index.php';
        $contenidoIndex = generarArchivoDesdePlantilla($nombre, $_POST['descripcion'], 'index');
        // Crear el archivo index.php con contenido dinámico    
        if (file_put_contents($archivoIndex, $contenidoIndex) !== false) {
            echo "Archivo 'index.php' creado exitosamente en el directorio '$nombre'.";
        } else {
            echo "Error al crear el archivo 'index.php' en el directorio '$nombre'.";
        }
    } else {
        echo "El campo 'nombre' es obligatorio.";
    }
}

function crearDirectorio($nombre) {
    $ruta = __DIR__ . '/negocio/' . $nombre;

    if (!is_dir($ruta)) {
        if (mkdir($ruta, 0755, true)) {
            echo "Directorio '$nombre' creado exitosamente.";
            $conexion = new mysqli('localhost', 'root', '', 'negocios');

            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }

            $sql = "CREATE TABLE IF NOT EXISTS `$nombre` (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255) NOT NULL,
                propietario VARCHAR(255) NOT NULL,
                descripcion TEXT,
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            if ($conexion->query($sql) === TRUE) {
                echo "Tabla '$nombre' creada exitosamente.";
            } else {
                echo "Error al crear la tabla: " . $conexion->error;
            }
            $stmt = $conexion->prepare("INSERT INTO `$nombre` (nombre, propietario, descripcion) VALUES (?, ?, ?)");
            if ($stmt) {
                $propietario = isset($_POST['propietario']) ? $_POST['propietario'] : 'Desconocido';
                $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : 'Sin descripción';
                $stmt->bind_param("sss", $nombre, $propietario, $descripcion);

                if ($stmt->execute()) {
                    echo "Datos insertados exitosamente en la tabla '$nombre'.";
                } else {
                    echo "Error al insertar los datos: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error al preparar la consulta: " . $conexion->error;
            }
            $conexion->close();
        } else {
            echo "Error al crear el directorio '$nombre'.";
        }
    } else {
        echo "El directorio '$nombre' ya existe.";
    }
}
?>

