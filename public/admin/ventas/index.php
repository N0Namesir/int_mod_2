<?php
session_start(); // Inicia o mantiene la sesión
include_once '../../../config/conexion.php'; // Asegura que la conexión es correcta
include_once 'headerAd.php'; // Incluye el encabezado de administración
require_once "../../../config/verificar_admin.php"; // Bloquea usuarios no admin

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php"); // Redirigir si no ha iniciado sesión
    exit();
}



// Obtener las ventas registradas
$result = $conn->query("SELECT * FROM ventas ORDER BY fecha_venta DESC");
$ventas = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <br>
    <br>
    <div class="container mt-5">
        <h1>Lista de Ventas</h1>
        <a href="agregar_venta.php" class="btn btn-success mb-3">Registrar Nueva Venta</a>
        <a href="informe_ventas.php" class="btn btn-success mb-3">Generar PDF</a>
        
        <?php if (count($ventas) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Venta</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?php echo $venta['id_venta']; ?></td>
                            <td><?php echo $venta['fecha_venta']; ?></td>
                            <td><?php echo $venta['cliente']; ?></td>
                            <td>
                                <a href="detalles_venta.php?id=<?php echo $venta['id_venta']; ?>" class="btn btn-info btn-sm">Ver</a>
                                <a href="editar_venta.php?id=<?php echo $venta['id_venta']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar_venta.php?id=<?php echo $venta['id_venta']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta venta?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-info">No hay ventas registradas.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>