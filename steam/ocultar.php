<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");

$id = intval($_GET['id']);

$conexion->query("UPDATE detalles_venta SET oculto = 1 WHERE id_detalle = $id");

header("Location: biblioteca.php");
