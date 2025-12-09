<?php
session_start();
require "conexion.php";

// ============================
// VERIFICAR LOGIN
// ============================
if (!isset($_SESSION['id_usuario'])) {
    die("Debes iniciar sesión.");
}

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'] ?? "usuario"; // por si no existe

// ============================
// OBTENER GÉNERO SELECCIONADO
// ============================
$genero = $_GET['genero'] ?? "";

// ============================
// CONSULTA PRINCIPAL
// ============================

// Si es admin → puede ver todos los juegos
// Si NO es admin → solo ve sus propios juegos
$sql = "SELECT * FROM videojuegos";

$condiciones = [];

if ($genero !== "") {
    $condiciones[] = "genero = '" . $conexion->real_escape_string($genero) . "'";
}

if ($rol !== "admin") {
    $condiciones[] = "id_usuario = " . intval($id_usuario);
}

if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

$resultado = $conexion->query($sql);

// ============================
// OBTENER TODOS LOS GÉNEROS
// ============================
$generos_query = $conexion->query("SELECT DISTINCT genero FROM videojuegos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Videojuegos</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial;
        }
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #1b2838;
        }
        a.boton {
            padding: 8px 12px;
            background-color: #2a475e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.boton:hover {
            background-color: #4f7a99;
        }
        .top {
            width: 90%;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .crear, .regresar {
            background-color: #66c0f4;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: black;
            font-weight: bold;
        }
        .crear:hover, .regresar:hover {
            background-color: #53a8d6;
        }
        img.thumb {
            width: 80px;
            border-radius: 4px;
        }
        .filtro-box {
            width: 90%;
            margin: 0 auto 20px auto;
            text-align: left;
        }
        select {
            padding: 6px;
            border-radius: 5px;
        }
        .btn-filtrar {
            padding: 6px 12px;
            background-color: #2a475e;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-filtrar:hover {
            background-color: #4f7a99;
        }
    </style>
</head>
<body>

<div class="top">
    <a class="regresar" href="index.php">⟵ Regresar</a>
    <h2>Lista de Videojuegos</h2>
    <a class="crear" href="crear_juego.php">➕ Agregar Nuevo Juego</a>
</div>

<!-- FILTRO DE GÉNEROS -->
<div class="filtro-box">
    <form method="GET" action="listar_juegos.php">
        <label>Filtrar por género:</label>
        <select name="genero">
            <option value="">Todos</option>

            <?php while ($g = $generos_query->fetch_assoc()) { ?>
                <option value="<?= $g['genero'] ?>"
                    <?= ($genero === $g['genero']) ? "selected" : "" ?>>
                    <?= ucfirst($g['genero']) ?>
                </option>
            <?php } ?>

        </select>

        <button class="btn-filtrar" type="submit">Aplicar</button>
    </form>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Precio</th>
        <th>Género</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>

    <?php if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($fila['id_videojuego']); ?></td>
            <td><?= htmlspecialchars($fila['titulo']); ?></td>
            <td>$<?= htmlspecialchars($fila['precio']); ?></td>
            <td><?= htmlspecialchars($fila['genero']); ?></td>
            <td>
                <?php if (!empty($fila['imagen'])) { ?>
                    <img class="thumb" src="imagenes/<?= htmlspecialchars($fila['imagen']); ?>" alt="Imagen">
                <?php } else { echo '-'; } ?>
            </td>
            <td>

                <?php if ($rol === "admin" || $fila["id_usuario"] == $id_usuario) { ?>
                    <a class="boton" href="editar_juego.php?id=<?= urlencode($fila['id_videojuego']); ?>">Editar</a>
                    <a class="boton" href="eliminar_juego.php?id=<?= urlencode($fila['id_videojuego']); ?>" onclick="return confirm('¿Seguro que deseas eliminar este juego?');">Eliminar</a>
                <?php } else { ?>
                    -
                <?php } ?>

            </td>
        </tr>
    <?php }
    } else { ?>
        <tr>
            <td colspan="6">No hay videojuegos disponibles.</td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
