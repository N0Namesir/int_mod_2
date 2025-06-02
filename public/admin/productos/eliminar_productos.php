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
    $sql = "DELETE FROM productos WHERE codigo_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<p class='alert alert-danger'>Error al eliminar el producto.</p>";
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
  <title>Eliminar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Eliminar Producto</h1>
  <p>
    ¿Estás seguro de eliminar el producto <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong> (Código: <?php echo htmlspecialchars($producto['codigo_producto']); ?>)?
  </p>
  <form method="POST" action="eliminar_producto.php?codigo=<?php echo urlencode($codigo); ?>">
    <button type="submit" class="btn btn-danger">Eliminar Producto</button>
    <a href="lista_productos.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>