<?php
session_start();

// Conexión
$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$carrito = $_SESSION['carrito'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial;
        }
        .contenedor {
            width: 70%;
            margin: auto;
            padding: 20px;
        }
        .card {
            background-color: #1b1b1b;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        img {
            width: 120px;
            border-radius: 8px;
        }
        .titulo {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .btn {
            padding: 10px;
            background-color: #2a475e;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #4f7a99;
        }
        .btn-danger {
            background-color: #b53737;
        }
        .btn-danger:hover {
            background-color: #872727;
        }
        form { margin: 0; }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>🛒 Tu carrito</h2>

    <?php if (empty($carrito)) : ?>

        <p>No tienes juegos en el carrito.</p>

    <?php else: ?>

        <?php
        // Sanitizar y convertir a enteros por seguridad
        $ids_int = array_map('intval', $carrito);
        // Quitar duplicados y posibles ceros no válidos
        $ids_int = array_values(array_unique(array_filter($ids_int, function($v){ return $v > 0; })));

        if (empty($ids_int)) {
            echo "<p>No hay juegos válidos en el carrito.</p>";
        } else {
            $ids = implode(",", $ids_int);
            // Ejecutar la consulta y comprobar errores
            $sql = "SELECT * FROM videojuegos WHERE id_videojuego IN ($ids)";
            $res = $conexion->query($sql);

            if ($res === false) {
                // Mostrar error de consulta para depuración (puedes quitar mysqli_error en producción)
                echo "<p>Error en la consulta: " . htmlspecialchars($conexion->error) . "</p>";
            } else {
                // Recorremos resultados
                while ($row = $res->fetch_assoc()): ?>
                    <div class="card">
                        <img src="imagenes/<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">

                        <div style="flex:1;">
                            <div class="titulo"><?php echo htmlspecialchars($row['titulo']); ?></div>
                            <div>Precio: $<?php echo number_format($row['precio'], 2); ?></div>
                        </div>

                        <!-- BOTÓN QUITAR -->
                        <form action="quitar_carrito.php" method="POST">
                            <input type="hidden" name="id_juego" value="<?php echo (int)$row['id_videojuego']; ?>">
                            <button type="submit" class="btn btn-danger">Quitar</button>
                        </form>

                    </div>
                <?php endwhile;
                $res->free();
            }
        }
        ?>

        <a class="btn" href="comprar_carrito.php">Comprar todo</a>

    <?php endif; ?>

    <br><br>

    <a class="btn" href="index.php">← Seguir comprando</a>

</div>

</body>
</html>
