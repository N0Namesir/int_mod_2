<?php
ob_start();  // Inicia el buffer para evitar salida previa
session_start();
include_once '../../../config/conexion.php';
require_once('../../../config/verificar_admin.php');
// No se incluye headerAd.php para evitar que se envíe salida HTML

require_once("../../../fpdf/fpdf.php");

class PDF extends FPDF {
    // Encabezado de página
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,utf8_decode('Reporte de Productos'),0,1,'C');
        $this->Ln(3);
        // Encabezados de columnas (sin la descripción)
        $this->SetFont('Arial','B',12);
        $this->Cell(40,10, 'Codigo', 1, 0, 'C');
        $this->Cell(50,10, 'Nombre', 1, 0, 'C');
        $this->Cell(40,10, 'Precio Costo', 1, 0, 'C');
        $this->Cell(40,10, 'Precio Venta', 1, 0, 'C');
        $this->Cell(30,10, 'Stock', 1, 1, 'C');
    }
    
    // Pie de página
    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Pagina '.$this->PageNo(),0,0,'C');
    }
}

$pdf = new PDF('L','mm','A4');  // Formato A4 en Landscape
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Consulta a la base de datos para obtener los datos
$sql = "SELECT codigo_producto, nombre, precio_costo, precio_venta, stock_actual FROM productos ORDER BY nombre ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(40,10, $row['codigo_producto'], 1, 0, 'C');
    $pdf->Cell(50,10, utf8_decode($row['nombre']), 1, 0, 'C');
    $pdf->Cell(40,10, number_format($row['precio_costo'],2), 1, 0, 'C');
    $pdf->Cell(40,10, number_format($row['precio_venta'],2), 1, 0, 'C');
    $pdf->Cell(30,10, $row['stock_actual'], 1, 1, 'C');
}

ob_end_clean();  // Limpia cualquier salida previa del buffer
$pdf->Output('D','Reporte_Productos_sinDescripcion.pdf');
exit();
?>