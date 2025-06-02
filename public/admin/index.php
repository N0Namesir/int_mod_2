<?php
session_start(); // Inicia o mantiene la sesión

// Verifica si el usuario está autenticado
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../../index.php"); // Redirigir si no ha iniciado sesión
    exit();
}

include_once '../../config/conexion.php';
require_once "../../config/verificar_admin.php"; // Bloquea usuarios no admin

include_once 'headerAd.php';

echo 'ere amin'

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="alert alert-warning text-center">
        <strong>⚠️ Aviso de Seguridad:</strong>
        Recuerda que cualquier modificación en el sistema debe hacerse con responsabilidad.
        Alteraciones perjudiciales o deliberadas podrían tener consecuencias legales.
    </div>

    <div class="alert alert-warning text-center">
        <strong>⚠️ Aviso de del personal de frontend:</strong>
        No vamos a hacer nada, porque no tenemos ni idea de como se hace, pero si quieres puedes hacerlo tú.
    </div>

    <!-- <div class="alert alert-warning text-center">
        <strong>⚠️ Aviso de los estudiantes:</strong>
        Este es un proyecto de estudiantes, por lo que no se garantiza su funcionamiento al 100%. Si encuentras algún error, por favor, infórmanos.
    </div> -->



</body>

</html>