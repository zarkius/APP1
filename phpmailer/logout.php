<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();
session_destroy();
header("Location: index.php");
exit();
?>
