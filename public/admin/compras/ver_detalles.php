<?php
session_start();
include_once '../../../config/conexion.php';
include_once 'headerAd.php';
require_once '../../../config/verificar_admin.php';

if (!isset($_SESSION["usuario"])) {
  header("Location: ../../../index.php");
  exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
  die("No se especificó una compra válida.");
}

$id_compra = $_GET['id'];

// Obtener la compra
$sqlCompra = "SELECT * FROM compras WHERE id_compra = ?";
$stmt = $conn->prepare($sqlCompra);
$stmt->bind_param("i", $id_compra);
$stmt->execute();
$result = $stmt->get_result();
$compra = $result->fetch_assoc();

if (!$compra) {
  die("Compra no encontrada.");
}

// Obtener los detalles de la compra
$sqlDetalles = "SELECT * FROM detalle_compra WHERE id_compra = ?";
$stmtDetalle = $conn->prepare($sqlDetalles);
$stmtDetalle->bind_param("i", $id_compra);
$stmtDetalle->execute();
$resultDetalle = $stmtDetalle->get_result();
$detalles = $resultDetalle->fetch_all(MYSQLI_ASSOC);

$totalCompra = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de Compra</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Detalle de Compra</h1>
  <div class="card mb-4">
    <div class="card-header">Información de la Compra</div>
    <div class="card-body">
      <p><strong>ID Compra:</strong> <?php echo $compra['id_compra']; ?></p>
      <p><strong>Fecha de Compra:</strong> <?php echo $compra['fecha_compra']; ?></p>
      <p><strong>Proveedor:</strong> <?php echo htmlspecialchars($compra['proveedor']); ?></p>
    </div>
  </div>
  <?php if(count($detalles) > 0): ?>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>ID Detalle</th>
          <th>Código Producto</th>
          <th>Cantidad Comprada</th>
          <th>Precio Compra</th>
          <th>Total Línea</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($detalles as $detalle): ?>
          <?php 
            $lineTotal = $detalle['cantidad_comprada'] * $detalle['precio_compra'];
            $totalCompra += $lineTotal;
          ?>
          <tr>
            <td><?php echo $detalle['id']; ?></td>
            <td><?php echo htmlspecialchars($detalle['codigo_producto']); ?></td>
            <td><?php echo $detalle['cantidad_comprada']; ?></td>
            <td>$<?php echo number_format($detalle['precio_compra'], 2); ?></td>
            <td>$<?php echo number_format($lineTotal, 2); ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="4" class="text-end"><strong>Total Compra:</strong></td>
          <td><strong>$<?php echo number_format($totalCompra, 2); ?></strong></td>
        </tr>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No hay detalles registrados para esta compra.</div>
  <?php endif; ?>
  <a href="index.php" class="btn btn-secondary">Volver a Compras</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>