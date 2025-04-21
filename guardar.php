<?php
        session_start();
        date_default_timezone_set('UTC');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

// Database connection
require __DIR__ . '/testdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $autor = $_SESSION['user']['name'];
    $texto = $_POST['texto'];
    $enlaces = $_POST['enlaces'];
    $sql = "INSERT INTO cursos (autor,texto, enlaces) VALUES ('$autor','$texto', '$enlaces')";

    if ($mysqli->query($sql) === TRUE) {
        echo "New course saved successfully.";
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

$mysqli->close();