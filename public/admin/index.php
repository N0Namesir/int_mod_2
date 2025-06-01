<?php
session_start(); // Inicia o mantiene la sesión

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: login.html"); // Redirigir si no ha iniciado sesión
    exit();
}

include_once '../../config/conexion.php';
require_once "../../config/verificar_admin.php"; // Bloquea usuarios no admin

include_once 'headerAd.php';

echo 'ere amin'

?>