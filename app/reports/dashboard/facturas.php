<?php
require('../../helpers/report.php');
require('../../models/clientes.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de compras realizadas en un día');
// Se instancia el módelo Categorías para obtener los datos.
$categoria = new Clientes;
// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataCategorias = $categoria->cargarDatos()) {
    // Se recorren los registros ($dataCategorias) fila por fila ($rowCategoria).
    foreach ($dataCategorias as $rowCategoria) {
    // Creamos un atributo para almacenar los subtotales
    $subtotal = 0;
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($categoria->setId($rowCategoria['id'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $categoria->cargarFacturas()) {
                // Se establece un color de relleno para mostrar el nombre de la categoría.
                $pdf->SetFillColor(0, 31, 97);
                // Se establece la fuente para el nombre de la categoría.
                $pdf->SetFont('Helvetica', 'B', 12);
                $pdf->SetTextColor(255);

                // Se imprime una celda con el nombre de la categoría.
                $pdf->Cell(0, 10, utf8_decode('Cliente: '.$rowCategoria['nombre']), 1, 1, 'C', 1);
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(225);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->SetTextColor(9,9,9);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(90, 10, utf8_decode('Producto'), 1, 0, 'C', 1);
                $pdf->Cell(50, 10, utf8_decode('Cantidad (Uds)'), 1, 0, 'C', 1);
                $pdf->Cell(46, 10, utf8_decode('Precio (USD)'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->SetTextColor(9,9,9);
                    $pdf->Cell(90, 10, utf8_decode($rowProducto['producto']), 1, 0);
                    $pdf->Cell(50, 10, utf8_decode($rowProducto['cantidad']), 1, 0);
                    $pdf->Cell(46, 10, '$'.$rowProducto['preciounitario'], 1, 1);
                    $subtotal = $subtotal + $rowProducto['preciounitario'];  
                }
                $pdf->Cell(20);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(290, 10, 'Costo total (USD): $' .$subtotal, 0, 1, 'C');
                // Se agrega un salto de línea para mostrar el contenido principal del documento.
                $pdf->Ln(5);
            } 
        } else {
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(0, 10, utf8_decode('Categoría incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay facturas para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>