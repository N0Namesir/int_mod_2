<?php
session_start();
include_once '../config/conexion.php';
include_once 'header.php';

if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit();
}

// Recoger el término de búsqueda, si se envía
$buscar = "";
if (isset($_GET['buscar'])) {
    $buscar = trim($_GET['buscar']);
}

// Construir la consulta en función del criterio de búsqueda
if (!empty($buscar)) {
    // Se buscan coincidencias en el nombre y en el código de producto
    $sql = "SELECT * FROM productos WHERE nombre LIKE ? OR codigo_producto LIKE ? ORDER BY nombre ASC";
    $stmt = $conn->prepare($sql);
    $param = "%" . $buscar . "%";
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Si no hay búsqueda, se muestran todos los productos
    $sql = "SELECT * FROM productos ORDER BY nombre ASC";
    $result = $conn->query($sql);
}

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
  <!-- Barra de búsqueda -->
  <form method="GET" action="productos.php" class="mb-3">
    <div class="input-group">
      <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o código" value="<?php echo htmlspecialchars($buscar); ?>">
      <button type="submit" class="btn btn-primary">Buscar</button>
    </div>
  </form>
  
  <!-- <a href="agregar_producto.php" class="btn btn-success mb-3">Agregar Producto</a> -->
  <!-- <a href="informe_productos.php" class="btn btn-success mb-3">Generar informe en PDF</a> -->
  
  <?php if (count($productos) > 0): ?>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Código</th>
          <th>Nombre</th>
          <!-- Puedes incluir otros campos a mostrar -->
          <!-- <th>Precio Costo</th> -->
          <th>Precio Venta</th>
          <th>Stock Actual</th>
          <!-- <th>Acciones</th> -->
        </tr>
      </thead>
      <tbody>
        <?php foreach($productos as $producto): ?>
          <tr>
            <td><?php echo htmlspecialchars($producto['codigo_producto']); ?></td>
            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
            <!-- <td>$<?php //echo number_format($producto['precio_costo'], 2); ?></td> -->
            <td>$<?php echo number_format($producto['precio_venta'], 2); ?></td>
            <td><?php echo $producto['stock_actual']; ?></td>
            <!-- <td>
              <a href="editar_producto.php?codigo=<?php echo urlencode($producto['codigo_producto']); ?>" class="btn btn-warning btn-sm">Editar</a>
              <a href="eliminar_productos.php?codigo=<?php echo urlencode($producto['codigo_producto']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">Eliminar</a>
            </td> -->
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No se encontraron productos.</div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>