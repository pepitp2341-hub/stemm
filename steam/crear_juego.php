<?php
session_start();
require "conexion.php";

// Verificar que el usuario esté logueado
if (!isset($_SESSION["id_usuario"])) {
    die("Debes iniciar sesión para agregar juegos.");
}

$mensaje = "";

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo = $_POST["titulo"];
    $precio = $_POST["precio"];
    $genero = $_POST["genero"];

    // ID del usuario dueño del juego
    $id_usuario = $_SESSION["id_usuario"];

    // Manejo de imagen
    $imagen = "";
    if (!empty($_FILES["imagen"]["name"])) {
        $imagen = basename($_FILES["imagen"]["name"]);
        $ruta = "imagenes/" . $imagen;
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
    }

    // Insertar en la BD incluyendo id_usuario
    $sql = "INSERT INTO videojuegos (titulo, precio, genero, imagen, id_usuario)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sdssi", $titulo, $precio, $genero, $imagen, $id_usuario);

    if ($stmt->execute()) {
        $mensaje = "?? Juego agregado correctamente.";
    } else {
        $mensaje = "? Error al agregar el juego: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar nuevo juego</title>
    <style>
        body {
            background-color: #10151c;
            color: white;
            font-family: Arial;
        }
        .contenedor {
            width: 50%;
            margin: 40px auto;
            background-color: #1b2838;
            padding: 20px;
            border-radius: 8px;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
        }
        .btn {
            padding: 12px 20px;
            background-color: #2a475e;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #3c6c8c;
        }
        .mensaje {
            background-color: #0f202d;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>Agregar Nuevo Videojuego</h2>

    <a href="index.php" class="btn">Regresar</a>
    <br><br>

    <?php if ($mensaje != "") { ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">

        <label>titulo:</label>
        <input type="text" name="titulo" required>

        <label>precio:</label>
        <input type="number" step="0.01" name="precio" required>

        <label>GENERO:</label>
        <select name="genero" required>
            <option value="">selecciona un genero</option>
            <option>accion</option>
            <option>aventura</option>
            <option>RPG</option>
            <option>disparo</option>
            <option>estrategia</option>
            <option>simulacion</option>
            <option>deportes</option>
        </select>

        <label>imagen del juego:</label>
        <input type="file" name="imagen" accept="image/*">

        <button class="btn" type="submit">Guardar Juego</button>
    </form>
</div>

</body>
</html>
