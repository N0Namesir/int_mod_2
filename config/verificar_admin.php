<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != "admin") {
    header("Location: ../../index.php"); // Redirige si no es admin
    exit();
}
?>