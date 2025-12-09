<?php
session_start();

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// =====================
// FILTROS RECIBIDOS
// =====================
$min = $_GET["min"] ?? "";
$max = $_GET["max"] ?? "";
$orden = $_GET["orden"] ?? "";
$genero = $_GET["genero"] ?? "";

// =====================
// CONSULTA PRINCIPAL
// =====================
$sql = "SELECT * FROM videojuegos WHERE 1=1";

if ($min !== "") $sql .= " AND precio >= $min";
if ($max !== "") $sql .= " AND precio <= $max";
if ($genero !== "") $sql .= " AND genero = '$genero'";

if ($orden == "asc")  $sql .= " ORDER BY precio ASC";
if ($orden == "desc") $sql .= " ORDER BY precio DESC";

$resultado = $conexion->query($sql);

// =====================
// JUEGOS COMPRADOS POR EL USUARIO
// =====================
$comprados = [];
$id_usuario = $_SESSION['id_usuario'] ?? 0;

if ($id_usuario > 0) {

    $q = $conexion->query("
        SELECT dv.id_videojuego
        FROM ventas v
        INNER JOIN detalles_venta dv ON v.id_venta = dv.id_venta
        WHERE v.id_usuario = $id_usuario
    ");

    while ($row_c = $q->fetch_assoc()) {
        $comprados[] = $row_c['id_videojuego'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda de Videojuegos</title>

    <style>
        body {
            margin: 0;
            background-color: #121212;
            color: white;
            font-family: Arial;
        }
        header {
            background-color: #1b2838;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .contenedor {
            width: 90%;
            margin: auto;
            padding: 20px 0;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .card {
            background-color: #1b1b1b;
            padding: 10px;
            border-radius: 8px;
            transition: 0.2s;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }
        .titulo {
            font-size: 18px;
            margin: 10px 0 5px;
        }
        .precio {
            color: #00ff66;
            font-weight: bold;
        }
        .boton {
            display: block;
            text-align: center;
            padding: 10px;
            background-color: #2a475e;
            margin-top: 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }
        .boton:hover {
            background-color: #4f7a99;
        }
        .comprado {
            background-color: #0f9d58;
            text-align: center;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .filtro-btn {
            padding: 10px 15px;
            background-color: #2a475e;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 15px;
            font-size: 16px;
        }
        #filtroMenu {
            display: none;
            background-color: #1f1f1f;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            width: 300px;
        }
        #filtroMenu input,
        #filtroMenu select {
            width: 100%;
            margin-top: 5px;
            margin-bottom: 15px;
            padding: 5px;
        }
    </style>

    <script>
        function toggleFiltros() {
            let menu = document.getElementById("filtroMenu");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        }
    </script>
</head>
<body>

<header>
    <div class="logo">MiSteam</div>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="biblioteca.php">Biblioteca</a>
        <a href="carrito.php">Carrito</a>

        <!-- SOLO EL ADMIN/MODERADOR (id_usuario = 1) VE EL CRUD -->
        <?php if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == 1): ?>
            <a href="lista_videojuegos.php">CRUD Videojuegos</a>
        <?php endif; ?>

        <a href="buscar.php">Buscador</a>

        <?php if (!isset($_SESSION['id_usuario'])): ?>
            <a href="acceso.php">Iniciar / Registrarse</a>
        <?php else: ?>
            <span>Hola, <?php echo $_SESSION['nombre']; ?></span>
            <a href="logout.php">Cerrar sesión</a>
        <?php endif; ?>
    </nav>
</header>

<div class="contenedor">

    <h2>Juegos disponibles</h2>

    <button class="filtro-btn" onclick="toggleFiltros()">Filtros ▼</button>

    <div id="filtroMenu">
        <form method="GET" action="index.php">

            <label>Precio mínimo:</label>
            <input type="number" name="min" value="<?= $min ?>">

            <label>Precio máximo:</label>
            <input type="number" name="max" value="<?= $max ?>">

            <label>Orden:</label>
            <select name="orden">
                <option value="">Sin orden</option>
                <option value="asc" <?= ($orden=="asc" ? "selected" : "") ?>>Menor a mayor</option>
                <option value="desc" <?= ($orden=="desc" ? "selected" : "") ?>>Mayor a menor</option>
            </select>

            <label>Género:</label>
            <select name="genero">
                <option value="">Todos</option>
                <option value="accion" <?= ($genero=="accion"?"selected":"") ?>>Acción</option>
                <option value="aventura" <?= ($genero=="aventura"?"selected":"") ?>>Aventura</option>
                <option value="deportes" <?= ($genero=="deportes"?"selected":"") ?>>Deportes</option>
                <option value="rpg" <?= ($genero=="rpg"?"selected":"") ?>>RPG</option>
            </select>

            <button class="filtro-btn" type="submit" style="width:100%;">Aplicar filtros</button>
        </form>
    </div>

    <!-- GRID DE JUEGOS -->
    <div class="grid">
        <?php while ($row = $resultado->fetch_assoc()) { ?>
        <div class="card">

            <img src="imagenes/<?php echo $row['imagen']; ?>">

            <div class="titulo"><?php echo $row['titulo']; ?></div>
            <div class="precio">$<?php echo $row['precio']; ?></div>

            <?php if (in_array($row['id_videojuego'], $comprados)): ?>

                <div class="comprado">✔ Comprado</div>

                <a class="boton" style="background-color:#cc9900;"
                   href="regalar.php?id=<?php echo $row['id_videojuego']; ?>">
                    🎁 Regalar
                </a>

            <?php else: ?>

                <a class="boton" href="detalles.php?id=<?php echo $row['id_videojuego']; ?>">Ver más</a>
                <a class="boton" href="agregar_carrito.php?id=<?php echo $row['id_videojuego']; ?>">Agregar al carrito</a>

                <a class="boton" style="background-color:#cc9900;"
                   href="regalar.php?id=<?php echo $row['id_videojuego']; ?>">
                    🎁 Regalar
                </a>

            <?php endif; ?>

        </div>
        <?php } ?>
    </div>

</div>

</body>
</html>
