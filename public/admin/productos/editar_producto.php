<?php
session_start();
include_once '../../../config/conexion.php';
include_once 'headerAd.php';
require_once '../../../config/verificar_admin.php';

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php");
    exit();
}

if (!isset($_GET["codigo"]) || empty($_GET["codigo"])) {
    die("Producto no especificado.");
}

$codigo = $_GET["codigo"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio_costo = $_POST["precio_costo"];
    $precio_venta = $_POST["precio_venta"];
    $marca = $_POST["marca"];
    $categoria = $_POST["categoria"];
    $stock_actual = $_POST["stock_actual"];
    
    $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio_costo = ?, precio_venta = ?, marca = ?, categoria = ?, stock_actual = ? WHERE codigo_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddssis", $nombre, $descripcion, $precio_costo, $precio_venta, $marca, $categoria, $stock_actual, $codigo);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<p class='alert alert-danger'>Error al actualizar el producto.</p>";
    }
}

$sql = "SELECT * FROM productos WHERE codigo_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $codigo);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    die("Producto no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Editar Producto (Código: <?php echo htmlspecialchars($producto['codigo_producto']); ?>)</h1>
  <form method="POST">
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre:</label>
      <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" maxlength="100" required>
    </div>
    <div class="mb-3">
      <label for="descripcion" class="form-label">Descripción:</label>
      <textarea name="descripcion" id="descripcion" class="form-control"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
    </div>
    <div class="mb-3">
      <label for="precio_costo" class="form-label">Precio Costo:</label>
      <input type="number" name="precio_costo" id="precio_costo" step="0.01" class="form-control" value="<?php echo $producto['precio_costo']; ?>" required>
    </div>
    <div class="mb-3">
      <label for="precio_venta" class="form-label">Precio Venta:</label>
      <input type="number" name="precio_venta" id="precio_venta" step="0.01" class="form-control" value="<?php echo $producto['precio_venta']; ?>" required>
    </div>
    <div class="mb-3">
      <label for="marca" class="form-label">Marca:</label>
      <input type="text" name="marca" id="marca" class="form-control" value="<?php echo htmlspecialchars($producto['marca']); ?>" maxlength="50">
    </div>
    <div class="mb-3">
      <label for="categoria" class="form-label">Categoría:</label>
      <input type="text" name="categoria" id="categoria" class="form-control" value="<?php echo htmlspecialchars($producto['categoria']); ?>" maxlength="50">
    </div>
    <div class="mb-3">
      <label for="stock_actual" class="form-label">Stock Actual:</label>
      <input type="number" name="stock_actual" id="stock_actual" class="form-control" value="<?php echo $producto['stock_actual']; ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="lista_productos.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>