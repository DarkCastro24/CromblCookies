<?php

class Facturas extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $usuario = null;
    private $estado = null;

    // METODOS PARA ASIGNAR LOS VALORES

    public function setId($value){

        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setUsuario($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    // Metodos GET

    public function getId()
    {
        return $this->id;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    // Metodo para cargar las facturas
    
    // Método para obtener todos los registros de pedidos (antes facturas)
    public function readAll()
    {
        $sql = "SELECT p.id AS id_pedido, CONCAT(c.nombres, ' ', c.apellidos) AS cliente, p.estado, p.fecha
                FROM pedidos p
                INNER JOIN clientes c ON p.id_cliente = c.id
                ORDER BY p.id";
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Método para obtener los datos de un pedido específico
    public function readOne()
    {
        $sql = 'SELECT p.id AS id_pedido, p.estado, p.fecha
                FROM pedidos p
                WHERE p.id = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar los datos de un pedido
    public function updateRow()
    {
        $sql = 'UPDATE pedidos 
                SET estado = ?, fecha = ?
                WHERE id = ?';
        $params = array($this->estado, $this->fecha, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Método para buscar pedidos por cliente
    public function searchRows($value)
    {
        $sql = "SELECT p.id AS id_pedido, CONCAT(c.nombres, ' ', c.apellidos) AS cliente, e.estadofactura
                FROM pedidos p
                INNER JOIN clientes c ON p.id_cliente = c.id
                INNER JOIN estadofactura e ON p.estado = e.id
                WHERE CONCAT(c.nombres, ' ', c.apellidos) ILIKE ?
                ORDER BY p.id";
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    // Método para cargar los detalles de un pedido
    public function cargarDatosParam($idPedido)
    {
        $sql = "SELECT CONCAT(c.categoria, ' ', pr.producto) AS producto, d.preciounitario, d.cantidad,
                d.preciounitario * d.cantidad AS totalunitario
                FROM detallepedidos d
                INNER JOIN pedidos pe ON d.id_pedido = pe.id
                INNER JOIN productos pr ON d.id_producto = pr.id
                INNER JOIN categorias c ON pr.id_categoria = c.id
                WHERE pe.id = ?
                ORDER BY d.id";
        $params = array($idPedido);
        return Database::getRows($sql, $params);
    }

    // Método para obtener el total de un pedido
    public function getTotalPrice()
    {
        $sql = 'SELECT SUM(preciounitario * cantidad) AS total 
                FROM detallepedidos 
                WHERE id_pedido = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
}