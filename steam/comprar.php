<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: acceso.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_SESSION['id_usuario'];
$total = 0;
$items = [];

// 🔹 SI VIENE UNA COMPRA INDIVIDUAL
if (isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $consulta = $conexion->query("SELECT precio FROM videojuegos WHERE id_videojuego = $id");

    if ($consulta->num_rows > 0) {
        $row = $consulta->fetch_assoc();
        $total = $row['precio'];
        $items[] = $id; // guardar el juego comprado
    }

} else {

    // 🔹 COMPRA DESDE EL CARRITO
    if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
        echo "<script>alert('Tu carrito está vacío.'); window.location='carrito.php';</script>";
        exit;
    }

    foreach ($_SESSION['carrito'] as $id) {
        if (!is_numeric($id)) continue;

        $consulta = $conexion->query("SELECT precio FROM videojuegos WHERE id_videojuego = $id");

        if ($consulta->num_rows > 0) {
            $row = $consulta->fetch_assoc();
            $total += $row['precio'];
            $items[] = $id;
        }
    }
}

// 🔹 INSERTAR VENTA
$conexion->query("INSERT INTO ventas (id_usuario, total, fecha) VALUES ($id_usuario, $total, NOW())");
$id_venta = $conexion->insert_id;

// 🔹 GUARDAR DETALLES
foreach ($items as $id) {
    $conexion->query("INSERT INTO detalles_venta (id_venta, id_videojuego, cantidad)
                      VALUES ($id_venta, $id, 1)");
}

// 🔹 LIMPIAR CARRITO SOLO SI LA COMPRA FUE DEL CARRITO
if (!isset($_GET['id'])) {
    unset($_SESSION['carrito']);
}

echo "<script>
    alert('Compra realizada con éxito!');
    window.location='index.php';
</script>";

?>
