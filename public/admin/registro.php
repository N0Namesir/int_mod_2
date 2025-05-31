<?php
include "../../config/conexion.php";
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
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>