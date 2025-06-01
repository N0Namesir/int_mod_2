<?php
session_start(); // Inicia o mantiene la sesión
include_once '../../../config/conexion.php'; 

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php"); // Redirigir si no ha iniciado sesión
    exit();
}

//archivos basicos
require_once "../../../config/verificar_admin.php"; // Bloquea usuarios no admin
include_once 'headerAd.php'; //barra para navegar



// Ejecutar la consulta para obtener las compras, ordenadas por fecha
$query  = "SELECT * FROM compras ORDER BY fecha DESC";
$result = $conn->query($query);
$compras = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $compras[] = $row;
    }
}
?>
