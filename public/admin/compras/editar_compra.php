<?php
session_start();
include_once '../../../config/conexion.php';
include_once 'headerAd.php';
require_once '../../../config/verificar_admin.php';

if(!isset($_SESSION["usuario"])){
  header("Location: ../../../index.php");
  exit();
}

if(!isset($_GET["id"]) || empty($_GET["id"])){
  die("Compra no especificada.");
}

$id_compra = $_GET["id"];

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $proveedor = $_POST["proveedor"];
  $sqlUpdate = "UPDATE compras SET proveedor = ? WHERE id_compra = ?";
  $stmt = $conn->prepare($sqlUpdate);
  $stmt->bind_param("si", $proveedor, $id_compra);
  if($stmt->execute()){
      header("Location: detalle_compra.php?id=" . $id_compra);
      exit();
  } else {
      echo "<p class='alert alert-danger'>Error al actualizar la compra.</p>";
  }
}

$sql = "SELECT * FROM compras WHERE id_compra = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_compra);
$stmt->execute();
$result = $stmt->get_result();
$compra = $result->fetch_assoc();

if(!$compra){
  die("Compra no encontrada.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Compra</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1>Editar Compra (ID: <?php echo $compra['id_compra']; ?>)</h1>
  <form method="POST">
    <div class="mb-3">
      <label for="proveedor" class="form-label">Proveedor:</label>
      <input type="text" name="proveedor" id="proveedor" class="form-control" value="<?php echo htmlspecialchars($compra['proveedor']); ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar Cambios</button>
    <a href="detalles_compra.php?id=<?php echo $compra['id_compra']; ?>" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>