<?php
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION["id_usuario"])) {
    die("Debes iniciar sesión para comprar.");
}

$id_usuario = (int) $_SESSION["id_usuario"];

// Verificar que exista carrito
if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {
    die("El carrito está vacío.");
}

// Sanitizar carrito: convertir a enteros, quitar duplicados y ceros
$carrito_raw = $_SESSION["carrito"];
$carrito = array_values(array_unique(array_filter(array_map('intval', (array)$carrito_raw), function($v){ return $v > 0; })));

if (empty($carrito)) {
    die("No hay juegos válidos en el carrito.");
}

// Conexión
$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// --- 1) Obtener total con seguridad ---
$ids = implode(",", $carrito); // ahora seguro porque todos son enteros positivos

$sql = "SELECT SUM(precio) AS total FROM videojuegos WHERE id_videojuego IN ($ids)";
$res = $conexion->query($sql);

if ($res === false) {
    die("Error en la consulta de total: " . htmlspecialchars($conexion->error));
}

$row = $res->fetch_assoc();
$total = $row['total'] ?? 0.0;
$total = (float)$total; // aseguramos que sea float

// --- 2) Insertar en tabla ventas ---
$stmt = $conexion->prepare("INSERT INTO ventas (id_usuario, fecha, total) VALUES (?, NOW(), ?)");
if ($stmt === false) {
    die("Error al preparar la inserción en ventas: " . htmlspecialchars($conexion->error));
}

// tipos: i = int, d = double (float)
if (!$stmt->bind_param("id", $id_usuario, $total)) {
    die("Error en bind_param (ventas): " . htmlspecialchars($stmt->error));
}

if (!$stmt->execute()) {
    die("Error al ejecutar la inserción en ventas: " . htmlspecialchars($stmt->error));
}

$id_venta = $stmt->insert_id;
$stmt->close();

// --- 3) Insertar detalles de la venta ---
$stmt = $conexion->prepare("INSERT INTO detalles_venta (id_venta, id_videojuego) VALUES (?, ?)");
if ($stmt === false) {
    // Si falla aquí, opcionalmente podrías eliminar la venta creada arriba para mantener consistencia
    die("Error al preparar la inserción en detalles_venta: " . htmlspecialchars($conexion->error));
}

foreach ($carrito as $id_juego_raw) {
    $id_juego = (int)$id_juego_raw;
    if ($id_juego <= 0) continue; // seguridad adicional

    if (!$stmt->bind_param("ii", $id_venta, $id_juego)) {
        // manejo de error (no abortamos la app sin dar info)
        $stmt->close();
        die("Error en bind_param (detalles_venta): " . htmlspecialchars($stmt->error));
    }

    if (!$stmt->execute()) {
        // Aquí podrías registrar el error y continuar o abortar y hacer rollback manual
        $stmt->close();
        die("Error al insertar detalle de venta para el juego ID {$id_juego}: " . htmlspecialchars($stmt->error));
    }
}

$stmt->close();

// --- 4) Vaciar carrito y mostrar confirmación ---
$_SESSION["carrito"] = [];

// Redirige/mostrar pantalla de éxito
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Compra realizada</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial;
            text-align: center;
            padding-top: 50px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #2a475e;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn:hover { background-color: #4f7a99; }
    </style>
</head>
<body>

<h1>¡Compra realizada con éxito! 🎉</h1>
<p>Se han añadido tus juegos a la biblioteca.</p>
<p><strong>Monto total:</strong> $<?php echo number_format($total, 2); ?></p>

<a class="btn" href="biblioteca.php">Ir a mi biblioteca</a>
<br><br>
<a class="btn" href="index.php">Volver a la tienda</a>

</body>
</html>
