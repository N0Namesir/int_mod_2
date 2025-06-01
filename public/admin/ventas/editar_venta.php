<?php
session_start(); // Inicia o mantiene la sesión

include_once '../../../config/conexion.php';      // Conexión a la BD
include_once 'headerAd.php';                        // Encabezado de administración
require_once '../../../config/verificar_admin.php'; // Bloquea usuarios no admin

// Verificar que el usuario esté autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php");
    exit();
}

// Verificar que se haya pasado el ID de la venta por GET
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("No se especificó una venta válida.");
}

$id_venta = $_GET["id"];

// Si se envía el formulario, procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos básicos
    $cliente = $_POST["cliente"];
    // Datos de los detalles
    $productos  = $_POST["productos"];   // Array con códigos de productos
    $cantidades = $_POST["cantidades"];  // Array con cantidades
    $precios    = $_POST["precios"];     // Array con precios unitarios

    // Actualizar la tabla 'ventas'
    $sqlUpdateVenta = "UPDATE ventas SET cliente = ? WHERE id_venta = ?";
    $stmtUpdateVenta = $conn->prepare($sqlUpdateVenta);
    $stmtUpdateVenta->bind_param("si", $cliente, $id_venta);
    $stmtUpdateVenta->execute();

    // Eliminar los detalles antiguos para reinsertarlos
    $sqlDeleteDetalles = "DELETE FROM detalle_venta WHERE id_venta = ?";
    $stmtDeleteDetalles = $conn->prepare($sqlDeleteDetalles);
    $stmtDeleteDetalles->bind_param("i", $id_venta);
    $stmtDeleteDetalles->execute();

    // Preparar la inserción de nuevos detalles
    $sqlInsertDetalle = "INSERT INTO detalle_venta (id_venta, codigo_producto, cantidad_vendida, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmtInsertDetalle = $conn->prepare($sqlInsertDetalle);

    foreach ($productos as $key => $codigo_producto) {
        $cantidad = $cantidades[$key];
        $precio   = $precios[$key];

        $stmtInsertDetalle->bind_param("isid", $id_venta, $codigo_producto, $cantidad, $precio);
        $stmtInsertDetalle->execute();
    }

    // Redirigir a la página de detalles de la venta actualizada
    header("Location: detalles_venta.php?id=" . $id_venta);
    exit();
}

// Recuperar la información actual de la venta
$sqlVenta = "SELECT * FROM ventas WHERE id_venta = ?";
$stmtVenta = $conn->prepare($sqlVenta);
$stmtVenta->bind_param("i", $id_venta);
$stmtVenta->execute();
$resultVenta = $stmtVenta->get_result();
$venta = $resultVenta->fetch_assoc();

if (!$venta) {
    die("Venta no encontrada.");
}

// Recuperar los detalles actuales de la venta
$sqlDetalles = "SELECT * FROM detalle_venta WHERE id_venta = ?";
$stmtDetalles = $conn->prepare($sqlDetalles);
$stmtDetalles->bind_param("i", $id_venta);
$stmtDetalles->execute();
$resultDetalles = $stmtDetalles->get_result();
$detalles = $resultDetalles->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1>Editar Venta (ID: <?php echo $venta['id_venta']; ?>)</h1>
    <form method="POST">
      <!-- Información básica de la venta -->
      <div class="mb-3">
        <label for="cliente" class="form-label">Cliente:</label>
        <input type="text" name="cliente" id="cliente" class="form-control" value="<?php echo htmlspecialchars($venta['cliente']); ?>" required>
      </div>

      <hr>
      <h3>Detalles de la Venta</h3>
      <div id="productos-container">
        <?php if (count($detalles) > 0): ?>
          <?php foreach ($detalles as $detalle): ?>
            <div class="detalle-item mb-3">
              <label class="form-label">Producto:</label>
              <input type="text" name="productos[]" class="form-control" placeholder="Código del producto" value="<?php echo htmlspecialchars($detalle['codigo_producto']); ?>" required>
              <input type="number" name="cantidades[]" class="form-control mt-2" placeholder="Cantidad" value="<?php echo $detalle['cantidad_vendida']; ?>" required>
              <input type="number" name="precios[]" class="form-control mt-2" placeholder="Precio unitario" step="0.01" value="<?php echo $detalle['precio_unitario']; ?>" required>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Si no hay detalles, se muestra al menos un campo vacío -->
          <div class="detalle-item mb-3">
              <label class="form-label">Producto:</label>
              <input type="text" name="productos[]" class="form-control" placeholder="Código del producto" required>
              <input type="number" name="cantidades[]" class="form-control mt-2" placeholder="Cantidad" required>
              <input type="number" name="precios[]" class="form-control mt-2" placeholder="Precio unitario" step="0.01" required>
          </div>
        <?php endif; ?>
      </div>
      <button type="button" class="btn btn-secondary" onclick="agregarProducto()">Agregar otro producto</button>
      <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
      <a href="detalles_venta.php?id=<?php echo $venta['id_venta']; ?>" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
  </div>

  <script>
    function agregarProducto() {
      let contenedor = document.getElementById("productos-container");
      let nuevoProducto = document.createElement("div");
      nuevoProducto.classList.add("detalle-item", "mb-3");
      nuevoProducto.innerHTML = `
        <label class="form-label">Producto:</label>
        <input type="text" name="productos[]" class="form-control" placeholder="Código del producto" required>
        <input type="number" name="cantidades[]" class="form-control mt-2" placeholder="Cantidad" required>
        <input type="number" name="precios[]" class="form-control mt-2" placeholder="Precio unitario" step="0.01" required>
      `;
      contenedor.appendChild(nuevoProducto);
    }
  </script>
</body>
</html>