<?php
require "conexion.php";

// Verificar si se envió el ID del juego
if (!isset($_GET['id'])) {
    die("ID de videojuego no proporcionado.");
}

$id = $_GET['id'];

// Obtener datos actuales del videojuego
$sql = $conexion->prepare("SELECT * FROM videojuegos WHERE id_videojuego = ?");
$sql->bind_param("i", $id);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows == 0) {
    die("Juego no encontrado.");
}

$datos = $resultado->fetch_assoc();

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $genero = $_POST['genero'];
    $imagen = $datos['imagen']; // Inicialmente conserva la imagen actual

    // Si el usuario subió una nueva imagen
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], "imagenes/" . $imagen);
    }

    // Actualizar datos
    $update = $conexion->prepare(
        "UPDATE videojuegos SET titulo = ?, precio = ?, genero = ?, imagen = ? WHERE id_videojuego = ?"
    );
    $update->bind_param("sdssi", $titulo, $precio, $genero, $imagen, $id);

    if ($update->execute()) {
        echo "<script>alert('Juego actualizado correctamente'); window.location='lista_videojuegos.php';</script>";
    } else {
        echo "Error al actualizar.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Videojuego</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial;
            padding: 20px;
        }
        .contenedor {
            width: 60%;
            margin: auto;
            background-color: #1b1b1b;
            padding: 20px;
            border-radius: 10px;
        }
        input, select {
            width: 95%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
        }
        .btn {
            background-color: #2a475e;
            padding: 12px 20px;
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #4f7a99;
        }
        .regresar {
            display: inline-block;
            padding: 10px 15px;
            background-color: #66c0f4;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="contenedor">

    <a href="lista_videojuegos.php" class="regresar">⟵ Regresar</a>

    <h2>Editar Videojuego</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Título:</label>
        <input type="text" name="titulo" value="<?php echo $datos['titulo']; ?>" required>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?php echo $datos['precio']; ?>" required>

        <label>Género:</label>
        <input type="text" name="genero" value="<?php echo $datos['genero']; ?>" required>

        <label>Imagen Actual:</label><br>
        <img src="imagenes/<?php echo $datos['imagen']; ?>" width="200" style="margin-top:10px; border-radius:5px;"><br>

        <label>Subir Nueva Imagen (opcional):</label>
        <input type="file" name="imagen">

        <button class="btn" type="submit">Guardar Cambios</button>

    </form>
</div>

</body>
</html>
