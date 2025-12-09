<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "tienda_videojuegos");

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// INICIAR SESIÓN
$mensaje = "";

if (isset($_POST['login'])) {

    $correo = $_POST['correo_login'];
    $password = $_POST['password_login'];

    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ? LIMIT 1");
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {

            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];

            header("Location: index.php");
            exit;
        } else {
            $mensaje = "Contraseña incorrecta.";
        }

    } else {
        $mensaje = "El correo no existe.";
    }
}

// REGISTRAR USUARIO
if (isset($_POST['registrar'])) {

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $check->bind_param("s", $correo);
    $check->execute();
    $resCheck = $check->get_result();

    if ($resCheck->num_rows > 0) {
        $mensaje = "Ese correo ya está registrado.";
    } else {
        $insert = $conexion->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $nombre, $correo, $password);

        if ($insert->execute()) {
            $mensaje = "Registrado correctamente. Ahora inicia sesión.";
        } else {
            $mensaje = "Error al registrar.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso - MiSteam</title>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial;
            margin: 0;
            padding: 0;
        }
        .contenedor {
            width: 400px;
            margin: 40px auto;
            background-color: #1b1b1b;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 5px;
        }
        .boton {
            width: 100%;
            padding: 10px;
            background-color: #2a475e;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }
        .boton:hover {
            background-color: #1b2838;
        }
        .mensaje {
            text-align: center;
            margin: 10px 0;
            color: #ff6666;
        }
        .regresar {
            display: inline-block;
            margin: 15px;
            padding: 10px 15px;
            background-color: #444;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }
        .titulo-seccion {
            margin-top: 20px;
            font-size: 20px;
            text-align: center;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<a class="regresar" href="index.php">⟵ Regresar</a>

<div class="contenedor">

    <h2>Acceso a MiSteam</h2>

    <?php if ($mensaje != ""): ?>
        <div class="mensaje"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- INICIAR SESIÓN -->
    <div class="titulo-seccion">Iniciar Sesión</div>
    <form method="POST">
        <input type="email" name="correo_login" placeholder="Correo electrónico" required>
        <input type="password" name="password_login" placeholder="Contraseña" required>
        <button class="boton" name="login">Entrar</button>
    </form>

    <!-- REGISTRARSE -->
    <div class="titulo-seccion">Registrarse</div>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button class="boton" name="registrar">Crear cuenta</button>
    </form>

</div>

</body>
</html>
