<?php
session_start();

// Conexión
$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Consultar todos los videojuegos
$sql = "SELECT * FROM videojuegos";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado de Juegos</title>
    <style>
        body {
            background-color: #121212;
            font-family: Arial;
            color: white;
        }
        .contenedor {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        table {
            width: 100%;
            background-color: #1b1b1b;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #333;
        }
        th {
            background-color: #2a475e;
        }
        img {
            width: 100px;
            border-radius: 6px;
        }
        .btn {
            padding: 8px 12px;
            text-decoration: none;
            background-color: #2a475e;
            color: white;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #4f7a99;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>📋 Lista de Videojuegos</h2>

    <a class="btn" href="index.php">← Volver al inicio</a>
    <a class="btn" href="crear_juego.php">➕ Agregar nuevo juego</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Título</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>

        <?php while ($row = $resultado->fetch_assoc()) : ?>
        <tr>
            <td><?= $row['id_videojuego']; ?></td>

            <td><img src="imagenes/<?= $row['imagen']; ?>"></td>

            <td><?= $row['titulo']; ?></td>

            <td>$<?= $row['precio']; ?></td>

            <td>
             
                <a class="btn" href="editar_juego.php?id=<?= $row['id_videojuego']; ?>">✏ Editar</a>
                <a class="btn" href="eliminar_juego.php?id=<?= $row['id_videojuego']; ?>"
                   onclick="return confirm('¿Seguro que deseas eliminar este juego?');">
                    ❌ Eliminar
                </a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>
</div>

</body>
</html>
