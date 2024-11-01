<?php
require('../../helpers/report.php');
require('../../models/categorias_producto.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de existencias agrupado por categoría');

// Se instancia el módelo Categorías para obtener los datos.
$categoria = new Categorias;
// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataCategorias = $categoria->readAll()) {
    // Se recorren los registros ($dataCategorias) fila por fila ($rowCategoria).
    foreach ($dataCategorias as $rowCategoria) {
        // Se establece un color de relleno para mostrar el nombre de la categoría.
        $pdf->SetFillColor(0, 31, 97);
        // Se establece la fuente para el nombre de la categoría.
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(255);
        // Se imprime una celda con el nombre de la categoría.
        $pdf->Cell(0, 10, utf8_decode('Categoría: '.$rowCategoria['categoria']), 1, 1, 'C', 1);
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($categoria->setIdcategoria($rowCategoria['idcategoria'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $categoria->readProductosCategoria()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(225);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->SetTextColor(9,9,9);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(54, 10, utf8_decode('Producto'), 1, 0, 'C', 1);
                $pdf->Cell(44, 10, utf8_decode('Marca'), 1, 0, 'C', 1);
                $pdf->Cell(44, 10, utf8_decode('Precio (USD)'), 1, 0, 'C', 1);
                $pdf->Cell(44, 10, utf8_decode('Existencias (Uds)'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataProductos as $rowProducto) {
                    $pdf->SetTextColor(9,9,9);
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->Cell(54, 10, utf8_decode($rowProducto['producto']), 1, 0);
                    $pdf->Cell(44, 10, utf8_decode($rowProducto['marca']), 1, 0);
                    $pdf->Cell(44, 10, '$'.$rowProducto['precio'], 1, 0);
                    $pdf->Cell(44, 10,utf8_decode($rowProducto['cantidad']), 1, 1);
                }
            } else {
                $pdf->SetTextColor(9,9,9);
                $pdf->Cell(0, 10, utf8_decode('No hay productos para esta categoría'), 1, 1);
            }
        } else {
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(0, 10, utf8_decode('Categoría incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay categorías para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>