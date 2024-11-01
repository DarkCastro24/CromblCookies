<?php
require('../../helpers/report.php');
require('../../models/productos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de productos más vendidos');

// Se instancia el módelo Categorías para obtener los datos.
$categoria = new Productos;
// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
    // Se recorren los registros ($dataCategorias) fila por fila ($rowCategoria).
        $pdf->SetFillColor(0, 31, 97);
        // Se establece la fuente para el nombre de la categoría.
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(255);
        // Se imprime una celda con el nombre de la categoría.
        $pdf->Cell(0, 10, utf8_decode('Productos más vendidos a menos vendidos'), 1, 1, 'C', 1);
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
            if ($dataProductos = $categoria->ventasCategorias()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(225);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->SetTextColor(9,9,9);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(60, 10, utf8_decode('Nombre producto'), 1, 0, 'C', 1);
                $pdf->Cell(47, 10, utf8_decode('Categoría'), 1, 0, 'C', 1);
                $pdf->Cell(46, 10, utf8_decode('Marca'), 1, 0, 'C', 1);
                $pdf->Cell(33, 10, utf8_decode('Ventas (Uds)'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->SetTextColor(9,9,9);
                    $pdf->Cell(60, 10, utf8_decode($rowProducto['producto']), 1, 0);
                    $pdf->Cell(47, 10, utf8_decode($rowProducto['categoria']), 1, 0);
                    $pdf->Cell(46, 10, utf8_decode($rowProducto['marca']), 1, 0);
                    $pdf->Cell(33, 10, utf8_decode($rowProducto['cantidad']), 1, 1);
                }
            } else {
                $pdf->SetTextColor(9,9,9);
                $pdf->Cell(0, 10, utf8_decode('No hay clientes para mostrar'), 1, 1);
            }
// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>