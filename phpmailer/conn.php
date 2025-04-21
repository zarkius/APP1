<?php 
// Database connection file
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$host="$_ENV[DB_HOST]";
$user="$_ENV[DB_USER]";
$pass="$_ENV[DB_PASSWORD]";
$db="two_step_verification";

$conn=mysqli_connect($host,$user,$pass,$db);
?>