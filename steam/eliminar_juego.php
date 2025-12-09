<?php
session_start();

// Validar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: acceso.php");
    exit;
}

// Validar que sea ADMIN (opcional — si tienes rol)
if (isset($_SESSION['rol']) && $_SESSION['rol'] != "admin") {
    echo "<script>alert('No tienes permisos para eliminar juegos.'); window.location='lista_videojuegos.php';</script>";
    exit;
}

// Verificar que viene el ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID no válido.'); window.location='lista_videojuegos.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// Conexión
$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");

if ($conexion->connect_error) {
    die("Error en conexión: " . $conexion->connect_error);
}

// Obtener imagen para borrarla también (opcional)
$consultaImg = $conexion->query("SELECT imagen FROM videojuegos WHERE id_videojuego = $id");

if ($consultaImg->num_rows > 0) {
    $row = $consultaImg->fetch_assoc();
    $imagen = $row['imagen'];

    if (file_exists("imagenes/" . $imagen)) {
        unlink("imagenes/" . $imagen);  // elimina imagen
    }
}

// Eliminar registro
$conexion->query("DELETE FROM videojuegos WHERE id_videojuego = $id");

echo "<script>
    alert('Juego eliminado correctamente.');
    window.location='lista_videojuegos.php';
</script>";

?>
