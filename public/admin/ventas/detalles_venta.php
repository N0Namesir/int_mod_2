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


// Verificar y obtener el ID de la venta desde la URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_venta = $_GET['id'];
} else {
    die("No se especificó una venta válida.");
}

// Consultar información de la venta en la tabla 'ventas'
$sqlVenta = "SELECT * FROM ventas WHERE id_venta = ?";
$stmtVenta = $conn->prepare($sqlVenta);
$stmtVenta->bind_param("i", $id_venta);
$stmtVenta->execute();
$resultVenta = $stmtVenta->get_result();
$venta = $resultVenta->fetch_assoc();

if (!$venta) {
    die("Venta no encontrada.");
}

// Consultar los detalles de la venta, uniendo con la tabla 'productos' para obtener info adicional
$sqlDetalles = "SELECT 
                    dv.id_detalle, 
                    dv.codigo_producto, 
                    dv.cantidad_vendida, 
                    dv.precio_unitario, 
                    p.nombre AS producto_nombre, 
                    p.descripcion AS producto_descripcion, 
                    p.marca, 
                    p.categoria
                FROM detalle_venta dv
                LEFT JOIN productos p ON dv.codigo_producto = p.codigo_producto
                WHERE dv.id_venta = ?";
$stmtDetalles = $conn->prepare($sqlDetalles);
$stmtDetalles->bind_param("i", $id_venta);
$stmtDetalles->execute();
$resultDetalles = $stmtDetalles->get_result();
$detalles = $resultDetalles->fetch_all(MYSQLI_ASSOC);

// Calcular el total de la venta
$totalVenta = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalles de Venta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1>Detalles de Venta</h1>
    <a href="index.php" class="btn btn-secondary mb-3">Volver a Ventas</a>

    <!-- Información de la venta -->
    <div class="card mb-4">
      <div class="card-header">Información de la Venta</div>
      <div class="card-body">
        <p><strong>ID Venta:</strong> <?php echo $venta['id_venta']; ?></p>
        <p><strong>Fecha de Venta:</strong> <?php echo $venta['fecha_venta']; ?></p>
        <p><strong>Cliente:</strong> <?php echo $venta['cliente']; ?></p>
      </div>
    </div>

    <!-- Listado de detalles -->
    <?php if(count($detalles) > 0): ?>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>ID Detalle</th>
            <th>Código Producto</th>
            <th>Producto</th>
            <th>Descripción</th>
            <th>Marca</th>
            <th>Categoría</th>
            <th>Cantidad Vendida</th>
            <th>Precio Unitario</th>
            <th>Total Línea</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($detalles as $detalle): ?>
            <?php 
              $lineTotal = $detalle['cantidad_vendida'] * $detalle['precio_unitario'];
              $totalVenta += $lineTotal;
            ?>
            <tr>
              <td><?php echo $detalle['id_detalle']; ?></td>
              <td><?php echo $detalle['codigo_producto']; ?></td>
              <td><?php echo $detalle['producto_nombre']; ?></td>
              <td><?php echo $detalle['producto_descripcion']; ?></td>
              <td><?php echo $detalle['marca']; ?></td>
              <td><?php echo $detalle['categoria']; ?></td>
              <td><?php echo $detalle['cantidad_vendida']; ?></td>
              <td>$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
              <td>$<?php echo number_format($lineTotal, 2); ?></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td colspan="8" class="text-end"><strong>Total Venta:</strong></td>
            <td><strong>$<?php echo number_format($totalVenta, 2); ?></strong></td>
          </tr>
        </tbody>
      </table>
    <?php else: ?>
      <p class="alert alert-info">No hay detalles registrados para esta venta.</p>
    <?php endif; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>