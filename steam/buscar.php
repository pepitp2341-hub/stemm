<?php
// buscar.php - Buscador de videojuegos
require "conexion.php";

$termino = "";
$resultado = [];

if (isset($_GET['q'])) {
    $termino = $_GET['q'];

    $sql = "SELECT * FROM videojuegos WHERE titulo LIKE ? OR genero LIKE ?";
    $stmt = $conexion->prepare($sql);
    $buscar = "%$termino%";
    $stmt->bind_param("ss", $buscar, $buscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial;
        }
        .contenedor {
            width: 80%;
            margin: 40px auto;
        }
        input[type=text] {
            width: 80%;
            padding: 12px;
            border-radius: 5px;
            border: none;
            margin-right: 10px;
        }
        .btn {
            padding: 12px 20px;
            background-color: #2a475e;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #4f7a99;
        }
        .resultado {
            background-color: #1b1b1b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        img {
            width: 150px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>Buscar videojuegos</h2>
    <a href="index.php" class="btn" style="margin-top:10px; display:inline-block;">⟵ Regresar</a>

    <form method="GET">
        <input type="text" name="q" placeholder="Escribe el nombre del juego o género..." value="<?php echo $termino; ?>" required>
        <button class="btn" type="submit">Buscar</button>
    </form>

    <?php if (isset($_GET['q'])) { ?>
        <h3>Resultados para: "<?php echo $termino; ?>"</h3>

        <?php if ($resultado->num_rows > 0) { ?>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                <div class="resultado">
                    <h3><?php echo $fila['titulo']; ?></h3>
                    <img src="imagenes/<?php echo $fila['imagen']; ?>" alt="Imagen">
                    <p><strong>Género:</strong> <?php echo $fila['genero']; ?></p>
                    <p><strong>Precio:</strong> $<?php echo $fila['precio']; ?></p>
                    <a class="btn" href="detalles.php?id=<?php echo $fila['id_videojuego']; ?>">Ver más</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No se encontraron resultados.</p>
        <?php } ?>
    <?php } ?>

</div>

</body>
</html>
