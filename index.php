<?php
include_once 'config/conexion.php'; 
include 'header.php';
echo 'index';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="styles/index.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card text-center">
        <h1>🌟 index 🌟</h1>
        <p>este es el index</p>
        <a href="dashboard.php" class="btn btn-primary mt-3">Ir al Dashboard</a>
    </div>
</body>
</html>