<?php
session_start();
$name = $_SESSION['user']['name'];
$email = $_SESSION['user']['email'];

?>

<form method="POST" action="guardar.php">
    <label for="text">Text:</label>
    <textarea id="text" name="texto" rows="4" cols="50" required></textarea><br><br>

    <label for="links">Links:</label>
    <input type="enlaces" id="links" name="enlaces"><br><br>

    <button type="submit">Submit</button>
</form>