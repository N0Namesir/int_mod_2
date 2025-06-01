<?php
include "../../config/conexion.php";
include_once 'headerAd.php';
require_once "../../config/verificar_admin.php"; // Bloquea usuarios no admin

//include_once '../../header.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST["nombre_usuario"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashear la contraseña
    $rol = $_POST["rol"];

    $sql = "INSERT INTO usuarios (nombre_usuario, password, rol) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre_usuario, $password, $rol);
    
    if ($stmt->execute()) {
        echo "Usuario registrado correctamente.";
        header("Location: index.php"); // Redirigir a la página de administración
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
include_once 'headerAd.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
</head>
<body>
    <h2>Registro de Usuario</h2>
    <form action="registro.php" method="POST">
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" id="nombre_usuario" name="nombre_usuario" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="rol">Rol:</label>
        <select id="rol" name="rol" required>
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select>

        <input type="submit" value="Registrarse">
    </form>
</body>
</html>