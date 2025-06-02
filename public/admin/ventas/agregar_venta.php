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
    // Recogemos datos básicos de la venta:
    $cliente = $_POST['cliente'];
    
    // Estos arrays contienen la información de cada línea de venta:
    $productos = $_POST['productos'];      // Código del producto
    $cantidades = $_POST['cantidades'];      // Cantidad vendida
    $precios = $_POST['precios'];            // Precio unitario

    // Iniciamos la transacción para asegurar integridad
    $conn->begin_transaction();
    try {
        // 1. Insertamos la venta en la tabla 'ventas'
        $sqlVenta = "INSERT INTO ventas (fecha_venta, cliente) VALUES (NOW(), ?)";
        $stmtVenta = $conn->prepare($sqlVenta);
        $stmtVenta->bind_param("s", $cliente);
        if (!$stmtVenta->execute()) {
            throw new Exception("Error al insertar la venta: " . $conn->error);
        }
        // Obtenemos el ID de la venta insertada
        $id_venta = $conn->insert_id;

        // Preparamos las consultas para procesar cada producto vendido:

        // Consulta para insertar el detalle en 'detalle_venta'
        $sqlInsertDetalle = "INSERT INTO detalle_venta (id_venta, codigo_producto, cantidad_vendida, precio_unitario) VALUES (?, ?, ?, ?)";
        $stmtDetalle = $conn->prepare($sqlInsertDetalle);
        
        // Consulta para obtener el stock actual del producto
        $sqlSelectStock = "SELECT stock_actual FROM productos WHERE codigo_producto = ?";
        $stmtSelect = $conn->prepare($sqlSelectStock);
        
        // Consulta para actualizar el stock del producto
        $sqlUpdateStock = "UPDATE productos SET stock_actual = stock_actual - ? WHERE codigo_producto = ?";
        $stmtUpdate = $conn->prepare($sqlUpdateStock);

        foreach ($productos as $key => $codigo_producto) {
            $cantidad = $cantidades[$key];
            $precio = $precios[$key];

            // 2. Verificar stock disponible
            $stmtSelect->bind_param("s", $codigo_producto);
            $stmtSelect->execute();
            $resultSelect = $stmtSelect->get_result();
            $producto = $resultSelect->fetch_assoc();
            if (!$producto) {
                throw new Exception("Producto no encontrado: $codigo_producto");
            }
            if ($producto['stock_actual'] < $cantidad) {
                throw new Exception("Stock insuficiente para el producto $codigo_producto. Stock disponible: " . $producto['stock_actual']);
            }

            // 3. Insertar el detalle de venta
            $stmtDetalle->bind_param("isid", $id_venta, $codigo_producto, $cantidad, $precio);
            if (!$stmtDetalle->execute()) {
                throw new Exception("Error al insertar el detalle de venta: " . $conn->error);
            }

            // 4. Actualizar el stock del producto
            $stmtUpdate->bind_param("is", $cantidad, $codigo_producto);
            if (!$stmtUpdate->execute()) {
                throw new Exception("Error al actualizar el stock del producto $codigo_producto: " . $conn->error);
            }
        }
        // Si todo salió bien, confirmamos la transacción
        $conn->commit();
        header("Location: detalles_venta.php?id=" . $id_venta);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p class='alert alert-danger'>".$e->getMessage()."</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Venta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Registrar Venta</h1>
  <form method="POST">
    <!-- Datos básicos de la venta -->
    <div class="mb-3">
      <label for="cliente" class="form-label">Cliente:</label>
      <input type="text" name="cliente" id="cliente" class="form-control" required>
    </div>
    <!-- Sección para los productos vendidos -->
    <div id="productos-container">
      <div class="mb-3">
        <label class="form-label">Código Producto:</label>
        <input type="text" name="productos[]" class="form-control" placeholder="Código del producto" required>
        <input type="number" name="cantidades[]" class="form-control mt-2" placeholder="Cantidad Vendida" required>
        <input type="number" name="precios[]" class="form-control mt-2" placeholder="Precio Unitario" step="0.01" required>
      </div>
    </div>
    <button type="button" class="btn btn-secondary" onclick="agregarProducto()">Agregar otro producto</button>
    <br><br>
    <button type="submit" class="btn btn-success">Registrar Venta</button>
  </form>
</div>
<script>
function agregarProducto() {
  let contenedor = document.getElementById("productos-container");
  let nuevoDiv = document.createElement("div");
  nuevoDiv.classList.add("mb-3");
  nuevoDiv.innerHTML = `
    <label class="form-label">Código Producto:</label>
    <input type="text" name="productos[]" class="form-control" placeholder="Código del producto" required>
    <input type="number" name="cantidades[]" class="form-control mt-2" placeholder="Cantidad Vendida" required>
    <input type="number" name="precios[]" class="form-control mt-2" placeholder="Precio Unitario" step="0.01" required>
  `;
  contenedor.appendChild(nuevoDiv);
}
</script>
</body>
</html>