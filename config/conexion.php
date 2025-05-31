<?php
$host = 'localhost';
$db   = 'tecnomundo';
$user = 'root';
$pass = 'sqlpass';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

