<?php
/*
*	Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/
class Detalle extends Validator
{
    //Metodo para cargar los detalles de la factura
    public function readAll()
    {
        $sql = 'SELECT idDetalleFactura, Concat(c.nombres,p.producto)  
        FROM detallepedidos d
        INNER JOIN productos p ON p.idproducto = d.producto
        INNER JOIN facturas pe ON pe.idFactura = d.pedido
        INNER JOIN clientes c ON c.idCliente  = pe.cliente';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
