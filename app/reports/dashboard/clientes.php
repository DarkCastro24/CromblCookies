<?php
require('../../helpers/report.php');
require('../../models/productos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de clientes ordenado por compras');
// Se instancia el módelo Categorías para obtener los datos.
$categoria = new Productos;
// Se establece un color de relleno para los encabezados.
$pdf->SetFillColor(0, 31, 97);
// Se establece la fuente para el nombre de la categoría.
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->SetTextColor(255);
// Se imprime una celda con el nombre de la categoría.
$pdf->Cell(0, 10, utf8_decode('Clientes con mas compras a menos compras'), 1, 1, 'C', 1);
// Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
if ($dataProductos = $categoria->comprasClientes()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->SetFillColor(225);
    // Se establece la fuente para los encabezados.
    $pdf->SetFont('Helvetica', 'B', 11);
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(68, 10, utf8_decode('Correo cliente'), 1, 0, 'C', 1);
    $pdf->Cell(64, 10, utf8_decode('Nombre completo'), 1, 0, 'C', 1);                
    $pdf->Cell(52, 10, utf8_decode('Nº compras (Uds)'), 1, 1, 'C', 1);
    // Se establece la fuente para los datos de los productos.
    $pdf->SetFont('Helvetica', '', 11);
    // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
    foreach ($dataProductos as $rowProducto) {
        // Se imprimen las celdas con los datos de los productos.
        $pdf->SetTextColor(9,9,9);
        $pdf->Cell(68, 10, utf8_decode($rowProducto['correo_electronico']), 1, 0);
        $pdf->Cell(64, 10, utf8_decode($rowProducto['nombre']), 1, 0);
        $pdf->Cell(52, 10, utf8_decode($rowProducto['cantidad']), 1, 1);
    }
} else {
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay clientes para mostrar'), 1, 1);
}
// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>