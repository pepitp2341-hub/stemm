<?php
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: acceso.php");
    exit;
}

// Validar que llegue un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID de juego inválido'); window.location='index.php';</script>";
    exit;
}

$id_juego = intval($_GET['id']);

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Evitar duplicados: si ya está en el carrito, no volver a agregar
if (!in_array($id_juego, $_SESSION['carrito'])) {
    $_SESSION['carrito'][] = $id_juego;
}

// Redirigir al carrito
header("Location: carrito.php");
exit;
?>
