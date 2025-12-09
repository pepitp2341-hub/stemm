<?php
session_start();
require "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];

    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña
        if (password_verify($contraseña, $usuario["contraseña"])) {
            $_SESSION["usuario_id"] = $usuario["id_usuario"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];

            header("Location: index.php");
            exit();
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "El correo no está registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        body {
            background-color: #1b2838;
            color: white;
            font-family: Arial;
        }
        .login-box {
            width: 350px;
            background: #2a475e;
            padding: 25px;
            margin: 120px auto;
            border-radius: 8px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: none;
        }
        .btn {
            background-color: #66c0f4;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #4ea6d8;
        }
        .mensaje {
            color: #ff7070;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Iniciar Sesión</h2>

    <form method="POST">
        <label>Correo:</label>
        <input type="email" name="correo" required>

        <label>Contraseña:</label>
        <input type="password" name="contraseña" required>

        <input class="btn" type="submit" value="Entrar">
    </form>

    <?php if ($mensaje != "") { ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php } ?>

</div>

</body>
</html>
