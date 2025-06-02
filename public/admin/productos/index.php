<?php
session_start();
include_once '../../../config/conexion.php';
include_once 'headerAd.php';
require_once '../../../config/verificar_admin.php';

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php");
    exit();
}

$sql = "SELECT * FROM productos ORDER BY codigo_producto ASC";
$result = $conn->query($sql);
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Lista de Productos</h1>
  <a href="agregar_producto.php" class="btn btn-success mb-3">Agregar Producto</a>
  <a href="informe_productos.php" class="btn btn-success mb-3">generar informe</a>
  <?php if(count($productos) > 0): ?>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Precio Costo</th>
          <th>Precio Venta</th>
          <th>Marca</th>
          <th>Categoría</th>
          <th>Stock Actual</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($productos as $producto): ?>
        <tr>
          <td><?php echo htmlspecialchars($producto['codigo_producto']); ?></td>
          <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
          <td><?php echo $producto['descripcion']; ?></td>
          <td>$<?php echo number_format($producto['precio_costo'], 2); ?></td>
          <td>$<?php echo number_format($producto['precio_venta'], 2); ?></td>
          <td><?php echo htmlspecialchars($producto['marca']); ?></td>
          <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
          <td><?php echo $producto['stock_actual']; ?></td>
          <td>
            <a href="editar_producto.php?codigo=<?php echo urlencode($producto['codigo_producto']); ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="eliminar_producto.php?codigo=<?php echo urlencode($producto['codigo_producto']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No hay productos registrados.</div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>