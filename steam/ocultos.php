<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("<script>alert('Debes iniciar sesión'); window.location='acceso.php';</script>");
}

$id_usuario = $_SESSION['id_usuario'];

$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");

$sql = "
    SELECT dv.id_detalle, vj.titulo, vj.precio, vj.imagen
    FROM ventas vt
    INNER JOIN detalles_venta dv ON vt.id_venta = dv.id_venta
    INNER JOIN videojuegos vj ON dv.id_videojuego = vj.id_videojuego
    WHERE vt.id_usuario = $id_usuario AND dv.oculto = 1
";

$res = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Juegos Ocultos</title>
    <style>
        body { background:#121212; color:white; font-family:Arial; margin:0; }
        .contenedor { width:90%; margin:auto; padding:20px 0; }
        .btn { background:#2a475e; padding:10px 18px; border-radius:6px; color:white; text-decoration:none; }
        .btn:hover { background:#4f7a99; }
        .grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
        .card { background:#1b1b1b; padding:10px; border-radius:8px; }
        img { width:100%; height:180px; object-fit:cover; border-radius:6px; }
        .visible { background:#5cb85c; padding:10px; display:block; text-align:center; border-radius:6px; color:white; text-decoration:none; margin-top:10px; }
        .visible:hover { background:#4cae4c; }
    </style>
</head>
<body>

<div class="contenedor">

    <a href="biblioteca.php" class="btn">⟵ Regresar</a>

    <h2>Juegos Ocultos</h2>

    <div class="grid">
        <?php while($row = $res->fetch_assoc()) { ?>
            <div class="card">
                <img src="imagenes/<?php echo $row['imagen']; ?>" alt="Juego">
                <div class="titulo"><?php echo $row['titulo']; ?></div>

                <a class="visible" href="mostrar.php?id=<?php echo $row['id_detalle']; ?>">Hacer visible</a>
            </div>
        <?php } ?>
    </div>

</div>

</body>
</html>
