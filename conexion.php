<?php
$host = 'localhost';
$user = 'root';  // Cambia esto si usas otro usuario
$password = '';  // Cambia esto si tienes contraseña
$dbname = 'tienda';  // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($host, $user, $password, $dbname);

// Verificar si la conexión es exitosa
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
