<?php
require_once 'vendor/autoload.php'; // Asegúrate de instalar firebase/php-jwt con Composer

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $credential = $data['credential'];

    // Decodificar el token JWT
    try {
        $decoded = JWT::decode($credential, new Key('YOUR_GOOGLE_PUBLIC_KEY', 'RS256')); // Reemplaza con la clave pública de Google
        $userInfo = (array) $decoded;

        // Responder con la información del usuario
        echo json_encode([
            'status' => 'success',
            'name' => $userInfo['name'] ?? 'Usuario',
            'email' => $userInfo['email'] ?? 'Correo no disponible',
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Token inválido: ' . $e->getMessage(),
        ]);
    }
}
?>