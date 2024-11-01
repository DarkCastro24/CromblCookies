<?php
/*
*	Clase para manejar las tablas facturas y detallepedidos de la base de datos. Es clase hija de Validator.
*/
class Pedidos extends Validator
{
    // Declaracion de atributos de la clase 
    private $id = null;
    private $factura = null;

    // Métodos para asignar el valor a los atributos
    public function setId($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    // Funciones para retornar el valor los atributos de la clase
    public function getId()
    {
        return $this->id;
    }
    
    public function getFactura()
    {
        return $this->factura;
    }

    // Método para cargar las facturas de un cliente
    public function cargarDatosParam($idCliente)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT f.idfactura,d.direccion,fecha
        from facturas f
        INNER JOIN clientes u ON u.idCliente = f.cliente 
        INNER JOIN direcciones d ON d.idDireccion = f.entrega 
        WHERE f.cliente = ? and f.estado = 2
        ORDER BY fecha DESC';
        // Cargamos los parametros en un arreglo 
        $params = array($idCliente);
        return Database::getRows($sql, $params);
    }    

    // Método para cargar los datos de una factura en especifico
    public function readOne()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT f.idfactura as id,u.usuario,d.direccion,fecha
        from facturas f
        INNER JOIN usuarios u ON u.idUsuario = f.vendedor 
        INNER JOIN direcciones d ON d.idDireccion = f.entrega 
        WHERE f.idfactura = ? and f.estado = 2';
        // Cargamos los parametros en un arreglo
        $params = array($this->id);
        // Verificamos si la obtenemos datos al ejecutar la consulta en la base
        if ($data = Database::getRow($sql, $params)) {
            $this->factura = $data['id'];
            return true;
        } else {
            return false;
        }
    }

    // Método para cargar los detalles de una factura y llenar el reporte 
    public function readReport()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT sum(preciounitario*d.cantidad) as precio
		FROM detallepedidos d
		INNER JOIN productos p ON p.idProducto = d.producto
		WHERE pedido = ?';
        // Cargamos los parametros en un arreglo
        $params = array($_SESSION['id_pedido']);
        // Obtenemos la data retornada por la consulta
        if ($data = Database::getRow($sql, $params)) {
            // Creamos una variable de sesion para guardar el precio total de la factura
            $_SESSION['preciototal'] = $data['precio'];
            // Creamos la sentencia SQL para cargar los datos de los detalles de la factura seleccionada
            $sql2 = 'SELECT iddetallefactura,p.producto,preciounitario,d.cantidad ,preciounitario*d.cantidad as total,m.marca
            FROM detallepedidos d
            INNER JOIN productos p ON p.idProducto = d.producto
			INNER JOIN marcas m ON m.idmarca = p.marca
            WHERE pedido = ?
            ORDER BY preciounitario desc';
            // Cargamos los parametros en un arreglo
        $params2 = array($_SESSION['id_pedido']);
            return Database::getRows($sql2, $params2);
        } else {
            return false;
        }   
    }

    // Método para cargar los detalles de una factura y llenar el reporte
    public function readReportCabecera()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT c.correo_electronico as cliente,fecha,u.usuario as vendedor ,c.nombres || ' ' || c.apellidos as nombre,c.dui
        FROM Facturas f 
        INNER JOIN clientes c ON c.idcliente = f.cliente
        INNER JOIN usuarios u ON u.idUsuario = f.vendedor
        WHERE idfactura = ?";
        // Cargamos los parametros en un arreglo
    $params = array($_SESSION['id_pedido']);
        return Database::getRows($sql, $params);  
    }
}
