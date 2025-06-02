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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productos = $_POST['productos'];   // Array de códigos de productos
    $cantidades = $_POST['cantidades'];   // Array de cantidades
    $precios = $_POST['precios'];         // Array de precios de compra

    $sqlDetalle = "INSERT INTO detalle_compra (id_compra, codigo_producto, cantidad_comprada, precio_compra) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlDetalle);

    foreach ($productos as $key => $codigo_producto) {
        $cantidad = $cantidades[$key];
        $precio = $precios[$key];
        $stmt->bind_param("isid", $id_compra, $codigo_producto, $cantidad, $precio);
        $stmt->execute();
    }
    header("Location: index.php?id=" . $id_compra);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Detalles a la Compra</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Agregar Detalles a la Compra (ID: <?php echo $id_compra; ?>)</h1>
  <form method="POST">
    <div id="detalle-container">
      <div class="detalle-item mb-3">
        <label class="form-label">Código Producto:</label>
        <input type="text" name="productos[]" class="form-control" placeholder="Código del producto" required>
        <input type="number" name="cantidades[]" class="form-control mt-2" placeholder="Cantidad Comprada" required>
        <input type="number" name="precios[]" step="0.01" class="form-control mt-2" placeholder="Precio Compra" required>
      </div>
    </div>
    <button type="button" class="btn btn-secondary" onclick="agregarDetalle()">Agregar otra compra</button>
    <button type="submit" class="btn btn-success mt-3">Guardar Detalles</button>
  </form>
</div>
<script>
function agregarDetalle() {
  let contenedor = document.getElementById("detalle-container");
  let nuevoDetalle = document.createElement("div");
  nuevoDetalle.classList.add("detalle-item", "mb-3");
  nuevoDetalle.innerHTML = `
    <label class="form-label">Código Producto:</label>
    <input type="text" name="productos[]" class="form-control" placeholder="Código del producto" required>
    <input type="number" name="cantidades[]" class="form-control mt-2" placeholder="Cantidad Comprada" required>
    <input type="number" name="precios[]" step="0.01" class="form-control mt-2" placeholder="Precio Compra" required>
  `;
  contenedor.appendChild(nuevoDetalle);
}
</script>
</body>
</html>