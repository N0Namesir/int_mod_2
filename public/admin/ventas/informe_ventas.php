<?php
ob_start();  // Inicia el buffer para evitar salida previa
session_start();
include_once '../../../config/conexion.php';
require_once('../../../config/verificar_admin.php');
// Se omite cualquier header HTML para que no interfiera la salida del PDF
require_once("../../../fpdf/fpdf.php");

class PDF extends FPDF {
    // Encabezado de página
    function Header() {
        // Configuración de fuente y título
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10, utf8_decode('Reporte de Ventas'), 0, 1, 'C');
        $this->Ln(3);
    }
    
    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10, 'Página '.$this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// Consulta para obtener las ventas (se ordena por fecha, de forma descendente)
$sqlVentas = "SELECT * FROM ventas ORDER BY fecha_venta DESC";
$resultVentas = $conn->query($sqlVentas);

while ($venta = $resultVentas->fetch_assoc()) {
    $id_venta = $venta['id_venta'];
    $fecha    = $venta['fecha_venta'];
    $cliente  = $venta['cliente'];
    
    // Calcular el total de la venta sumando cada detalle
    $sqlTotal = "SELECT SUM(cantidad_vendida * precio_unitario) AS total FROM detalle_venta WHERE id_venta = ?";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->bind_param("i", $id_venta);
    $stmtTotal->execute();
    $resTotal = $stmtTotal->get_result()->fetch_assoc();
    $totalVenta = $resTotal['total'] ?: 0;
    
    // Imprimir los datos principales de la venta
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,8, "ID Venta: ".$id_venta, 0, 0);
    $pdf->Cell(60,8, "Fecha: ".$fecha, 0, 0);
    $pdf->Cell(80,8, "Cliente: ".utf8_decode($cliente), 0, 1);
    $pdf->Cell(40,8, "Total: $".number_format($totalVenta,2), 0, 1);
    
    // Imprimir encabezado de la tabla de detalles
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(40,8, 'Codigo Prod', 1, 0, 'C');
    $pdf->Cell(30,8, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(40,8, 'Precio Unit.', 1, 0, 'C');
    $pdf->Cell(40,8, 'Subtotal', 1, 1, 'C');
    
    // Consultar los detalles de la venta
    $sqlDetalle = "SELECT * FROM detalle_venta WHERE id_venta = ?";
    $stmtDetalle = $conn->prepare($sqlDetalle);
    $stmtDetalle->bind_param("i", $id_venta);
    $stmtDetalle->execute();
    $resultDetalle = $stmtDetalle->get_result();
    $pdf->SetFont('Arial','',11);
    
    while ($detalle = $resultDetalle->fetch_assoc()) {
        $codigoProd  = $detalle['codigo_producto'];
        $cantidad    = $detalle['cantidad_vendida'];
        $precioUnit  = $detalle['precio_unitario'];
        $subtotal    = $cantidad * $precioUnit;
        
        $pdf->Cell(40,8, $codigoProd, 1, 0, 'C');
        $pdf->Cell(30,8, $cantidad, 1, 0, 'C');
        $pdf->Cell(40,8, "$".number_format($precioUnit,2), 1, 0, 'C');
        $pdf->Cell(40,8, "$".number_format($subtotal,2), 1, 1, 'C');
    }
    
    // Separador entre ventas
    $pdf->Ln(5);
}

ob_end_clean();  // Limpia el buffer de salida
$pdf->Output('D','Reporte_Ventas.pdf');
exit();
?>