<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
session_start();
include 'conn.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        ?>
            <script>
    alert("Registration Successful.");
    function navigateToPage() {
        window.location.href = 'index.php';
    }
    window.onload = function() {
        navigateToPage();
    }
</script>
        <?php 
    } else {
       echo "<script> alert('Registration Failed. Try Again');</script>";
    }
}
// Conectar a la base de datos y obtener todos los emails
$sql = "SELECT email FROM users";
$result = mysqli_query($conn, $sql);

if ($result) {
    $emails = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $emails[] = $row['email'];
    }
    // Opcional: imprimir los emails para verificar
    // print_r($emails);
} else {
    die("Error al obtener los emails: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        #container{
            border: 1px solid black;
            width: 450px;
            padding: 20px;
            margin-left: 400px;
            margin-top: 50px;
        }
        form{
            margin-left: 50px;
        }
        input[type=text],input[type=password]{
            width: 300px;
            height: 20px;
            padding: 10px;
        }
        label{
            font-size: 20px;
            font-weight: bold;
        }
        a{
            text-decoration: none;
            font-weight: bold;
            font-size: 21px;
            color: blue;
        }
        a:hover{
            cursor: pointer;
            color: purple;
        }
        input[type=submit]{
            width: 70px;
            background-color: blue;
            border: 1px solid blue;
            color: white;
            font-weight: bold;
            padding: 7px;
            margin-left: 130px;
        }
        input[type=submit]:hover{
            background-color: purple;
            cursor: pointer;
            border: 1px solid purple;
        }
    </style>
</head>
<body>
    <div id="container">
        <form method="post" action="registration.php">
            <label for="username">Username:</label><br>
            <input type="text" name="name" placeholder="Enter Username" required><br><br>

            <label for="email">Email:</label><br>
            <?php
            if (!empty($emails)) {
                echo '<input type="text" name="email" placeholder="Enter Your Email" required onblur="checkEmail(this.value)"><br><br>';
                echo '<script>
                    function checkEmail(email) {
                        const existingEmails = ' . json_encode($emails) . ';
                        if (existingEmails.includes(email)) {
                            const messageDiv = document.createElement("div");
                            messageDiv.style.color = "red";
                            messageDiv.style.fontWeight = "bold";
                            messageDiv.innerHTML = "Email already exists. Redirecting to login in <span id=\"countdown\">5</span> seconds.";
                            if (!document.querySelector("#emailExistsMessage")) {
                                messageDiv.id = "emailExistsMessage";
                                document.querySelector("form").prepend(messageDiv);
                            }
                            let countdown = 5;
                            const interval = setInterval(() => {
                                countdown--;
                                document.getElementById("countdown").textContent = countdown;
                                if (countdown === 0) {
                                    clearInterval(interval);
                                    window.location.href = "index.php";
                                }
                            }, 1000);
                        }
                    }
                </script>';
            } else {
                echo '<input type="text" name="email" placeholder="Enter Your Email" required><br><br>';
            }
            ?>

            <label for="password">Password:</label><br>
            <input type="password" name="password" placeholder="Enter Password" required><br><br>
            <input type="submit" name="register" value="Register"><br><br>
            <label>Already have an account? </label><a href="index.php">Login</a>
        </form>
    </div>

</body>
</html>