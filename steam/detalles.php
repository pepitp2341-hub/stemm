<?php
// detalles.php - Mostrar información completa de un videojuego
require "conexion.php";

// Validar que llega un ID
if (!isset($_GET["id"])) {
    echo "ID de juego no proporcionado.";
    exit;
}

$id = $_GET["id"];

// Consultar datos del videojuego
$sql = "SELECT * FROM videojuegos WHERE id_videojuego = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Juego no encontrado.";
    exit;
}

$juego = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del videojuego</title>
    <style>
        body {
            background-color: #10151c;
            color: white;
            font-family: Arial;
        }
        .contenedor {
            width: 60%;
            margin: 40px auto;
            background-color: #1b2838;
            padding: 25px;
            border-radius: 10px;
        }
        img {
            width: 300px;
            border-radius: 8px;
        }
        .btn {
            padding: 12px 20px;
            background-color: #2a475e;
            border: none;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .btn:hover {
            background-color: #3c6c8c;
        }
        .titulo {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .precio {
            font-size: 22px;
            color: #66c0f4;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <a href="index.php" class="btn">⟵ Regresar</a>
	<a href="comprar.php?id=<?php echo $juego['id_videojuego']; ?>" class="btn">Comprar</a>

    <br><br>

    <h2 class="titulo"><?php echo $juego['titulo']; ?></h2>

    <img src="imagenes/<?php echo $juego['imagen']; ?>" alt="Imagen del juego">

    <p class="precio">Precio: $<?php echo $juego['precio']; ?></p>
    <p><strong>Género:</strong> <?php echo $juego['genero']; ?></p>
</div>

</body>
</html>
