<?php
session_start(); // Inicia o mantiene la sesión

include_once '../../../config/conexion.php';      // Conexión a la BD
include_once 'headerAd.php';                        // Encabezado de administración
require_once '../../../config/verificar_admin.php'; // Bloquea usuarios no admin

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php");
    exit();
}

// Verifica si se pasó el ID de la venta por GET
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("No se especificó una venta válida.");
}

$id_venta = $_GET["id"];

// Procesar el formulario de detalles cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productos  = $_POST["productos"];  // Array con códigos del producto
    $cantidades = $_POST["cantidades"]; // Array con cantidades vendidas
    $precios    = $_POST["precios"];    // Array con precios unitarios

    // Preparar la inserción en la tabla `detalle_venta`
    $sqlDetalle = "INSERT INTO detalle_venta (id_venta, codigo_producto, cantidad_vendida, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmtDetalle = $conn->prepare($sqlDetalle);

    foreach ($productos as $key => $codigo_producto) {
        $cantidad = $cantidades[$key];
        $precio   = $precios[$key];

        $stmtDetalle->bind_param("isid", $id_venta, $codigo_producto, $cantidad, $precio);
        $stmtDetalle->execute();
    }

    // Redirigir a la página de detalles de la venta una vez guardados los datos
    header("Location: detalles_venta.php?id=" . $id_venta);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Detalles a la Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Agregar Detalles a la Venta (ID: <?php echo $id_venta; ?>)</h1>
        <form method="POST">
            <div id="productos-container">
                <div class="mb-3">
                    <label class="form-label">Producto:</label>
                    <input type="text" name="productos[]" class="form-control" placeholder="Código del producto" required>
                    <input type="number" name="cantidades[]" class="form-control mt-2" placeholder="Cantidad" required>
                    <input type="number" name="precios[]" class="form-control mt-2" placeholder="Precio unitario" step="0.01" required>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="agregarProducto()">Agregar otro producto</button>
            <button type="submit" class="btn btn-success mt-3">Guardar Detalles</button>
        </form>
    </div>

    <script>
        function agregarProducto() {
            let contenedor = document.getElementById("productos-container");
            let nuevoProducto = document.createElement("div");
            nuevoProducto.classList.add("mb-3");
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