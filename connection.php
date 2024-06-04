<?php

require_once("config.php");

$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASSWORD;
$database = DB_NAME;

try {
    // Crear conexión
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Establecer el modo de error PDO en excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexión exitosa";
} catch(PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
?>