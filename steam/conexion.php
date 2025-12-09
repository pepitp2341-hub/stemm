<?php
$host = "localhost";      // Servidor
$usuario = "root";        // Usuario de MySQL
$contraseña = "";         // Contraseña (vacía por defecto en XAMPP)
$base_datos = "tienda_videojuegos";  // Nombre de la BD

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configurar charset
$conexion->set_charset("utf8");
?>
