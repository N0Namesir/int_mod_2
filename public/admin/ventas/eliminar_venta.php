<?php
session_start(); // Inicia o mantiene la sesión

include_once '../../../config/conexion.php';      // Conexión a la base de datos
include_once 'headerAd.php';                        // Encabezado de administración
require_once '../../../config/verificar_admin.php'; // Bloquea usuarios no admin

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php"); // Redirige si no ha iniciado sesión
    exit();
}

// Verifica que se haya pasado el ID de la venta por GET
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("No se especificó ninguna venta para eliminar.");
}

$id_venta = $_GET["id"];

// Si la petición es POST, procesamos la eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Iniciar una transacción para asegurar la integridad
    $conn->begin_transaction();
    try {
        // Paso 1: Eliminar los detalles de la venta
        $sqlDetalles = "DELETE FROM detalle_venta WHERE id_venta = ?";
        $stmtDetalles = $conn->prepare($sqlDetalles);
        $stmtDetalles->bind_param("i", $id_venta);
        $stmtDetalles->execute();

        // Paso 2: Eliminar la venta
        $sqlVenta = "DELETE FROM ventas WHERE id_venta = ?";
        $stmtVenta = $conn->prepare($sqlVenta);
        $stmtVenta->bind_param("i", $id_venta);
        $stmtVenta->execute();

        // Confirmar la transacción
        $conn->commit();

        // Redirigir a la lista de ventas después de eliminar satisfactoriamente
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p class='alert alert-danger'>Error al eliminar la venta: " . $e->getMessage() . "</p>";
    }
}

// Si la petición es GET, mostrar la página de confirmación
$sqlVentaInfo = "SELECT * FROM ventas WHERE id_venta = ?";
$stmtVentaInfo = $conn->prepare($sqlVentaInfo);
$stmtVentaInfo->bind_param("i", $id_venta);
$stmtVentaInfo->execute();
$resultVentaInfo = $stmtVentaInfo->get_result();
$venta = $resultVentaInfo->fetch_assoc();

if (!$venta) {
    die("Venta no encontrada.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
      <h1>Eliminar Venta</h1>
      <p>
          ¿Estás seguro de eliminar la venta con ID <strong><?php echo $venta['id_venta']; ?></strong>
          realizada al cliente <strong><?php echo htmlspecialchars($venta['cliente']); ?></strong>?
      </p>
      <form method="POST" action="eliminar_venta.php?id=<?php echo $id_venta; ?>">
          <button type="submit" class="btn btn-danger">Eliminar Venta</button>
          <a href="lista_ventas.php" class="btn btn-secondary">Cancelar</a>
      </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>