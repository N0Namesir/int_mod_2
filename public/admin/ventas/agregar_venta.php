<?php
session_start(); // Inicia o mantiene la sesión

include_once '../../../config/conexion.php';      // Asegura la conexión a la BD
include_once 'headerAd.php';                        // Encabezado de administración
require_once '../../../config/verificar_admin.php'; // Bloquea usuarios no admin

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php"); // Redirige si no ha iniciado sesión
    exit();
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente = $_POST["cliente"];

    // Insertar la venta en la tabla `ventas`
    $sqlVenta = "INSERT INTO ventas (fecha_venta, cliente) VALUES (NOW(), ?)";
    $stmtVenta = $conn->prepare($sqlVenta);
    $stmtVenta->bind_param("s", $cliente);

    if ($stmtVenta->execute()) {
        // Obtiene el id de venta generado
        $id_venta = $conn->insert_id;
        // Redirige al formulario para agregar detalles a la venta
        header("Location: agregar_detalles.php?id=" . $id_venta);
        exit();
    } else {
        echo "<p class='alert alert-danger'>Error al registrar la venta.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta - Paso 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Registrar Nueva Venta</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="cliente" class="form-label">Cliente:</label>
                <input type="text" name="cliente" id="cliente" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Guardar Venta y Agregar Detalles</button>
        </form>
    </div>
</body>
</html>