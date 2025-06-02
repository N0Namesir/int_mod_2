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
    $proveedor = $_POST['proveedor'];

    $sqlCompra = "INSERT INTO compras (proveedor, fecha_compra) VALUES (?, NOW())";
    $stmt = $conn->prepare($sqlCompra);
    $stmt->bind_param("s", $proveedor);
    if ($stmt->execute()) {
        $id_compra = $conn->insert_id;
        header("Location: detalles_compra.php?id=" . $id_compra);
        exit();
    } else {
        echo "<p class='alert alert-danger'>Error al registrar la compra.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Compra - Paso 1</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Registrar Nueva Compra</h1>
  <form method="POST">
    <div class="mb-3">
      <label for="proveedor" class="form-label">Proveedor:</label>
      <input type="text" name="proveedor" id="proveedor" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar Compra y Agregar Detalles</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>