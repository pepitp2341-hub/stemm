<?php
session_start();

if (!isset($_POST["id_juego"])) {
    die("ID no recibido.");
}

$id = $_POST["id_juego"];

if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

// ELIMINAR SOLO UNA OCURRENCIA DEL ARRAY
$index = array_search($id, $_SESSION["carrito"]);
if ($index !== false) {
    unset($_SESSION["carrito"][$index]);
    $_SESSION["carrito"] = array_values($_SESSION["carrito"]); // reindexar
}

header("Location: carrito.php");
exit();
