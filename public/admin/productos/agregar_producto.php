<?php
session_start();
include_once '../../../config/conexion.php';
include_once 'headerAd.php';
require_once '../../../config/verificar_admin.php';

if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_producto = $_POST['codigo_producto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $marca = $_POST['marca'];
    $categoria = $_POST['categoria'];
    $stock_actual = $_POST['stock_actual'];
    
    $sql = "INSERT INTO productos (codigo_producto, nombre, descripcion, precio_costo, precio_venta, marca, categoria, stock_actual) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdssdi", $codigo_producto, $nombre, $descripcion, $precio_costo, $precio_venta, $marca, $categoria, $stock_actual);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<p class='alert alert-danger'>Error al agregar el producto.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Agregar Nuevo Producto</h1>
  <form method="POST">
    <div class="mb-3">
      <label for="codigo_producto" class="form-label">Código Producto:</label>
      <input type="text" name="codigo_producto" id="codigo_producto" class="form-control" maxlength="20" required>
    </div>
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre:</label>
      <input type="text" name="nombre" id="nombre" class="form-control" maxlength="100" required>
    </div>
    <div class="mb-3">
      <label for="descripcion" class="form-label">Descripción:</label>
      <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
    </div>
    <div class="mb-3">
      <label for="precio_costo" class="form-label">Precio Costo:</label>
      <input type="number" name="precio_costo" id="precio_costo" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="precio_venta" class="form-label">Precio Venta:</label>
      <input type="number" name="precio_venta" id="precio_venta" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="marca" class="form-label">Marca:</label>
      <input type="text" name="marca" id="marca" class="form-control" maxlength="50">
    </div>
    <div class="mb-3">
      <label for="categoria" class="form-label">Categoría:</label>
      <input type="text" name="categoria" id="categoria" class="form-control" maxlength="50">
    </div>
    <div class="mb-3">
      <label for="stock_actual" class="form-label">Stock Actual:</label>
      <input type="number" name="stock_actual" id="stock_actual" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Agregar Producto</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>