<?php
session_start();
require "conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    die("Debes iniciar sesión para enviar regalos.");
}

$id_emisor = $_SESSION["id_usuario"];

if (!isset($_GET["id"])) {
    die("Falta el ID del videojuego.");
}

$id_videojuego = intval($_GET["id"]);

// =============================
// OBTENER VIDEOJUEGO
// =============================
$sql = "SELECT * FROM videojuegos WHERE id_videojuego = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_videojuego);
$stmt->execute();
$video = $stmt->get_result()->fetch_assoc();

if (!$video) {
    die("El videojuego no existe.");
}

// =============================
// LISTA DE USUARIOS
// =============================
$usuarios = $conexion->query("SELECT id_usuario, nombre FROM usuarios WHERE id_usuario != $id_emisor");

// SI NO HAY USUARIOS
if ($usuarios->num_rows === 0) {
    die("<h2>No hay otros usuarios a quien regalarle.</h2>");
}

// =============================
// PROCESAR FORMULARIO
// =============================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST["id_receptor"]) || empty($_POST["id_receptor"])) {
        die("Debes seleccionar un usuario para regalar.");
    }

    $id_receptor = intval($_POST["id_receptor"]);

    // Verificar que el receptor exista realmente
    $ver = $conexion->query("SELECT id_usuario FROM usuarios WHERE id_usuario = $id_receptor");

    if ($ver->num_rows == 0) {
        die("El usuario receptor no existe.");
    }

    // INSERTAR REGALO
    $sql = "INSERT INTO regalos (id_emisor, id_receptor, id_videojuego) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iii", $id_emisor, $id_receptor, $id_videojuego);

    if ($stmt->execute()) {

        // ---------------- INTERFAZ BONITA ---------------- //
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Regalo enviado</title>
            <style>
                body {
                    margin: 0;
                    background-color: #121212;
                    color: white;
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }

                .box {
                    background-color: #1f1f1f;
                    padding: 40px;
                    width: 450px;
                    text-align: center;
                    border-radius: 15px;
                    box-shadow: 0 0 20px rgba(0,0,0,0.4);
                    animation: aparecer 0.4s ease-out;
                }

                @keyframes aparecer {
                    from { opacity: 0; transform: scale(0.85); }
                    to   { opacity: 1; transform: scale(1); }
                }

                .icono { font-size: 60px; color: #00ff66; margin-bottom: 15px; }
                .titulo { font-size: 26px; margin-bottom: 10px; font-weight: bold; }
                .texto { font-size: 18px; margin-bottom: 25px; color: #cccccc; }

                a.boton {
                    display: inline-block;
                    padding: 12px 20px;
                    background-color: #2a475e;
                    color: white;
                    border-radius: 8px;
                    text-decoration: none;
                    font-size: 18px;
                    transition: 0.2s;
                }

                a.boton:hover { background-color: #4f7a99; }
            </style>
        </head>
        <body>

            <div class="box">
                <div class="icono">🎁</div>
                <div class="titulo">¡Regalo enviado!</div>
                <div class="texto">
                    Has enviado <strong><?= $video["titulo"] ?></strong> correctamente.
                </div>
                <a href="index.php" class="boton">Volver a la tienda</a>
            </div>

        </body>
        </html>
        <?php
        exit;
    } else {
        echo "Error al enviar regalo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Regalar videojuego</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial;
        }
        .contenedor {
            width: 450px;
            margin: 50px auto;
            background-color: #1b1b1b;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
        }
        select, button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border-radius: 6px;
            border: none;
        }
        button {
            background-color: #2a475e;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #4f7a99;
        }
        h2 { text-align: center; }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>🎁 Regalar Juego</h2>

    <p>Juego seleccionado:</p>
    <b><?= $video["titulo"] ?></b>

    <form method="POST">
        <label>Selecciona al usuario que recibirá el regalo:</label>
        <select name="id_receptor" required>
            <option value="">Selecciona...</option>

            <?php while ($u = $usuarios->fetch_assoc()) { ?>
                <option value="<?= $u["id_usuario"] ?>">
                    <?= $u["nombre"] ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit">Enviar regalo 🎁</button>
    </form>
</div>

</body>
</html>
