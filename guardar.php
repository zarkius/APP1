<?php
        session_start();
        date_default_timezone_set('UTC');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $servername = "localhost";
        $username = "insertData";
        $password = "11211121aA.,";
        $dbname = "app1";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $autor = $_SESSION['user']['name'];
    $texto = $_POST['texto'];
    $enlaces = $_POST['enlaces'];
    $sql = "INSERT INTO cursos (autor,texto, enlaces) VALUES ('$autor','$texto', '$enlaces')";

    if ($conn->query($sql) === TRUE) {
        echo "New course saved successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();