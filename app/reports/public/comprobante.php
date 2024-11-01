<?php
require('../../helpers/report_client.php');
require('../../models/historial_pedidos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Comprobante de compra');
// Se instancia el módelo Categorías para obtener los datos.
$categoria = new Pedidos;

    // Se establece el espacio entre elementos 
    $pdf->Ln(0);    
    $pdf->SetFillColor(0, 0, 0);
    // Se establece la fuente para el nombre de la categoría.
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->SetTextColor(255);
    // Se imprime una celda con el nombre de la categoría.
    $pdf->Cell(0, 10, utf8_decode('Productos adquiridos'), 1, 1, 'C', 1);
    // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
    if ($dataProductos = $categoria->readReport()) {
        // Se establece un color de relleno para los encabezados.
        $pdf->SetFillColor(225);
        // Se establece la fuente para los encabezados.
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(9,9,9);
        // Se imprimen las celdas con los encabezados.
        $pdf->Cell(56, 10, utf8_decode('Nombre producto'), 1, 0, 'C', 1);
        $pdf->Cell(40, 10, utf8_decode('Marca'), 1, 0, 'C', 1);
        $pdf->Cell(32, 10, utf8_decode('Precio $'), 1, 0, 'C', 1);
        $pdf->Cell(26, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $pdf->Cell(32, 10, utf8_decode('Total $'), 1, 1, 'C', 1);
        // Se establece la fuente para los datos de los productos.
        $pdf->SetFont('Helvetica', '', 11);
        // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
        foreach ($dataProductos as $rowProducto) {
            // Se imprimen las celdas con los datos de los productos.
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(56, 10, utf8_decode($rowProducto['producto']), 1, 0);
            $pdf->Cell(40, 10, utf8_decode($rowProducto['marca']), 1, 0);
            $pdf->Cell(32, 10, utf8_decode('$'.$rowProducto['preciounitario']), 1, 0);
            $pdf->Cell(26, 10, utf8_decode($rowProducto['cantidad']), 1, 0);
            $pdf->Cell(32, 10, utf8_decode('$'.$rowProducto['total']), 1, 1);
        }
        $pdf->Ln(5);
        $pdf->Cell(20);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(285, 10, ('Precio total (USD): $'. $_SESSION['preciototal']), 0, 1, 'C');
    } else {
        $pdf->SetTextColor(9,9,9);
        $pdf->Cell(0, 10, utf8_decode('No hay clientes para mostrar'), 1, 1);
    }
// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>