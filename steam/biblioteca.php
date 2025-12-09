<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    die("<script>alert('Debes iniciar sesión'); window.location='acceso.php';</script>");
}

$id_usuario = $_SESSION['id_usuario'];

// Conexión
$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Mostrar juegos (NO OCULTOS)
$sql = "
    SELECT dv.id_detalle, vj.id_videojuego, vj.titulo, vj.precio, vj.imagen, dv.oculto
    FROM ventas vt
    INNER JOIN detalles_venta dv ON vt.id_venta = dv.id_venta
    INNER JOIN videojuegos vj ON dv.id_videojuego = vj.id_videojuego
    WHERE vt.id_usuario = $id_usuario AND dv.oculto = 0
";

$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>
    <style>
        body { background:#121212; color:white; font-family:Arial; margin:0; }
        header { background:#1b2838; padding:15px; display:flex; justify-content:space-between; }
        header a { color:white; margin:0 10px; text-decoration:none; }

        .contenedor { width:90%; margin:auto; padding:20px 0; }

        .btn {
            display:inline-block;
            background:#2a475e;
            padding:10px 18px;
            border-radius:6px;
            color:white;
            text-decoration:none;
            margin-bottom:20px;
        }
        .btn:hover { background:#4f7a99; }

        .grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }

        .card { background:#1b1b1b; padding:10px; border-radius:8px; }

        img { width:100%; height:180px; object-fit:cover; border-radius:6px; }

        .titulo { font-size:18px; margin:10px 0 5px; }

        .ocultar {
            background:#d9534f; 
            padding:10px; 
            display:block; 
            text-align:center; 
            border-radius:6px; 
            color:white; 
            text-decoration:none;
            margin-top:10px;
        }
        .ocultar:hover { background:#c9302c; }
    </style>
</head>
<body>

<header>
    <div class="logo">MiSteam</div>

    <nav>
        <a href="index.php">Inicio</a>
        <a href="biblioteca.php">Biblioteca</a>
        <a href="buscar.php">Buscador</a>
        <span>Hola, <?php echo $_SESSION["nombre"]; ?></span>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
</header>

<div class="contenedor">

    <a href="index.php" class="btn">⟵ Regresar</a>
    <a href="ocultos.php" class="btn">👁️ Ver juegos ocultos</a>

    <h2>Tu Biblioteca</h2>

    <?php if ($resultado->num_rows == 0): ?>
        <p>No tienes juegos visibles (puede que tengas ocultos).</p>
    <?php else: ?>
        
    <div class="grid">
        <?php while($row = $resultado->fetch_assoc()) { ?>
            <div class="card">
                <img src="imagenes/<?php echo $row['imagen']; ?>" alt="Juego">

                <div class="titulo"><?php echo $row['titulo']; ?></div>
                <p>Precio: $<?php echo $row['precio']; ?></p>

                <a class="ocultar" href="ocultar.php?id=<?php echo $row['id_detalle']; ?>">Ocultar</a>
            </div>
        <?php } ?>
    </div>

    <?php endif; ?>

</div>

</body>
</html>
