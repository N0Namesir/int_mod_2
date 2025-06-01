<?php
include "config/conexion.php"; // Asegura que la conexión es correcta
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST["nombre_usuario"];
    $password = $_POST["password"];

    $sql = "SELECT id_usuario, nombre_usuario, password, rol FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql); // Ahora usando $conn en lugar de $pdo
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario["password"])) {
            $_SESSION["usuario"] = $usuario["nombre_usuario"];
            $_SESSION["rol"] = $usuario["rol"];
            
            header("Location: " . ($usuario["rol"] == "admin" ? "public/admin/" : "public/productos.php"));
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form action="index.php" method="POST">
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" id="nombre_usuario" name="nombre_usuario" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>