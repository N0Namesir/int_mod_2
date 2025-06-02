<?php
session_start();
include_once '../../../config/conexion.php';
include_once 'headerAd.php';
require_once '../../../config/verificar_admin.php';

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php");
    exit();
}

$sql = "SELECT * FROM compras ORDER BY fecha_compra DESC";
$result = $conn->query($sql);
$compras = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Compras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Compras</h1>
  <a href="agregar_compra.php" class="btn btn-success mb-3">Agregar Compra</a>
  <?php if(count($compras) > 0): ?>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>ID Compra</th>
          <th>Fecha Compra</th>
          <th>Proveedor</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($compras as $compra): ?>
        <tr>
          <td><?php echo $compra['id_compra']; ?></td>
          <td><?php echo $compra['fecha_compra']; ?></td>
          <td><?php echo htmlspecialchars($compra['proveedor']); ?></td>
          <td>
            <a href="ver_detalles.php?id=<?php echo $compra['id_compra']; ?>" class="btn btn-info btn-sm">Ver Detalles</a>
            <a href="editar_compra.php?id=<?php echo $compra['id_compra']; ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="eliminar_compra.php?id=<?php echo $compra['id_compra']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta compra?');">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No hay registros de compras.</div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>