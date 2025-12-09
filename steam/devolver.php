<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Debes iniciar sesión'); window.location='acceso.php';</script>";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_juego = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_juego <= 0) {
    echo "<script>alert('ID inválido'); window.location='index.php';</script>";
    exit;
}

$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");

if ($conexion->connect_error) {
    die("Error: " . $conexion->connect_error);
}

// 🔍 VERIFICAR SI EL JUEGO ESTÁ COMPRADO
$consulta = $conexion->prepare("
    SELECT id, devuelto FROM juegos_comprados
    WHERE id_usuario = ? AND id_videojuego = ?
");
$consulta->bind_param("ii", $id_usuario, $id_videojuego);
$consulta->execute();
$result = $consulta->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Este juego no estaba comprado'); window.location='index.php';</script>";
    exit;
}

$compra = $result->fetch_assoc();
$id_compra = $compra['id'];

// 🟢 MARCAR COMO DEVUELTO (no eliminar)
$update = $conexion->prepare("UPDATE juegos_comprados SET devuelto = 1 WHERE id = ?");
$update->bind_param("i", $id_compra);
$update->execute();

// Ahora en tu index.php o donde muestres los juegos,
// revisa el campo 'devuelto' para cambiar el botón:

// Ejemplo en HTML/PHP:
if ($compra['devuelto'] == 1) {
    echo "<button onclick=\"window.location='comprar.php?id=$id_juego'\">Comprar de nuevo</button>";
} else {
    echo "<button disabled>Comprado</button>";
}

echo "<script>alert('Juego devuelto exitosamente'); window.location='index.php';</script>";
exit;
?>
