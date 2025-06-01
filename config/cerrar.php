<?php
session_start();
session_unset(); // Limpia todas las variables de sesión
session_destroy(); // Elimina todas las variables de sesión
header("Location: ../index.php"); // Redirige al login después de cerrar sesión
exit();
?>