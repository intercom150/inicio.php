<?php
$servername = "localhost";
$username = "root"; // Cambia esto por tu nombre de usuario de la base de datos
$password = "mysql123"; // Cambia esto por tu contraseña de la base de datos
$dbname = "intercom";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
