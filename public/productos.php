<?php
session_start(); // Inicia o mantiene la sesión
include_once '../config/conexion.php';
include 'header.php';

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: login.html"); // Redirigir si no ha iniciado sesión
    exit();
}




// Ejecutar la consulta para obtener los productos, ordenados por nombre
$query = "SELECT * FROM productos ORDER BY codigo_producto ASC";
$result = $conn->query($query);

$productos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>
    <br>
    <br>

    <div class="container mt-5">
        <h1>Lista de Productos</h1>
        <!-- <a href="crear.php" class="btn btn-success mb-3">Crear Nuevo Producto</a>
        <a href="informe.php" class="btn btn-success mb-3">Crear Informe</a>
         -->
        <?php if (count($productos) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <!-- <th>Precio Costo ($)</th> -->
                        <th>Precio Venta ($)</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <!-- <th>Acciones</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo $producto['codigo_producto']; ?></td>
                            <td><?php echo $producto['nombre']; ?></td>
                            <td><?php echo $producto['descripcion']; ?></td>
                            <!-- <td><?php //echo $producto['precio_costo']; ?></td> -->
                            <td><?php echo $producto['precio_venta']; ?></td>
                            <td><?php echo $producto['marca']; ?></td>
                            <td><?php echo $producto['categoria']; ?></td>
                            <td><?php echo $producto['stock_actual']; ?></td>
                            <!-- <td>
                               <a href="ver.php?codigo=<?php //echo $producto['codigo_producto']; ?>" class="btn btn-info btn-sm">Ver</a>
                                <a href="editar.php?codigo=<?php //echo $producto['codigo_producto']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar.php?codigo=<?php //echo $producto['codigo_producto']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Estás seguro de eliminar este producto?');">
                                   Eliminar
                                </a> 
                            </td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-info">No hay productos registrados.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>