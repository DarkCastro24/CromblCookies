<?php
/*
*	Clase para manejar las tablas pedidos y detalle_pedido de la base de datos. Es clase hija de Validator.
*/
class Pedidos extends Validator
{
    // Declaracion de atributos para pedidos
    private $id_pedido = null;
    private $id_detalle = null;
    private $cliente = null;
    private $producto = null;
    private $cantidad = null;
    private $precio = null;
    private $estado = null; 
    private $cantidadStock = null;

    // Declaracion de atributos para direcciones
    private $iddireccion=null;
    private $municipio=null;
    private $direccion=null;
    private $codigopostal=null;
    private $telefonofijo=null;
    private $cmbdireccion=null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */

    public function setIdPedido($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->id_pedido = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdDetalle($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->id_detalle = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCliente($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setProducto($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidad($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->cantidad = $value;
            return true;
        } else {
            return false;
        }
    }

    
    public function setCantidadStock($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->cantidadStock = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setPrecio($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateMoney($value)) {
            $this->precio = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    // Funciones para retornar el valor los atributos de la clase

    public function getIdPedido()
    {
        return $this->id_pedido;
    }

    // Método para verificar si existe un pedido en proceso para seguir comprando, de lo contrario se crea uno.
    public function startOrder()
    {
        $this->estado = 0;

        $sql = 'SELECT id
                FROM pedidos
                WHERE estado = ? AND id_cliente = ?';
        $params = array($this->estado, $_SESSION['id_cliente']);
        if ($data = Database::getRow($sql, $params)) {
            $this->id_pedido = $data['id'];
            return true;
        } else {
            $sql = 'INSERT INTO pedidos(id, estado, id_cliente,fecha)
                    VALUES(default, ?, ?, default)';
            $params = array($this->estado, $_SESSION['id_cliente']);
            // Se obtiene el último valor insertado en la llave primaria de la tabla pedidos.
            if ($this->id_pedido = Database::getLastRow($sql, $params)) {
                return true;
            } else {
                return false;
            }
        }
    }
    // Método para agregar un producto al carrito de compras.
    public function createDetail()
    {
        // Se realiza una subconsulta para obtener el precio del producto.
        $sql = 'INSERT INTO detallepedidos(id, id_pedido, id_producto, preciounitario, cantidad)
                VALUES(default, ?, ?, (SELECT precio FROM productos p WHERE p.id = ?), ?)';
        $params = array($this->id_pedido, $this->producto, $this->producto, $this->cantidad);
        // Intentamos ejecutar la consulta y capturamos cualquier excepción.
        try {
            if (Database::executeRow($sql, $params)) {
                return true;
            } else {
                throw new Exception('Ocurrió un problema al agregar el producto');
            }
        } catch (Exception $e) {
            // Puedes registrar el error o mostrarlo directamente.
            error_log($e->getMessage());
            $this->exception = Database::getException() ?: $e->getMessage();
            return false;
        }
    }

    // Método para obtener los productos que se encuentran en el carrito de compras.
    public function readOrderDetail()
    {
        $sql = 'SELECT d.id, p.producto, d.preciounitario, d.cantidad, p.imagen, c.categoria, m.marca
                FROM pedidos pe
                INNER JOIN detallepedidos d ON d.id_pedido = pe.id
                INNER JOIN productos p ON p.id = d.id_producto
                INNER JOIN categorias c ON c.id = p.id_categoria
                INNER JOIN marcas m ON m.id = p.id_marca
                WHERE pe.id = ?';
        $params = array($this->id_pedido);
        return Database::getRows($sql, $params);
    }

    public function readHistorial($id_cliente)
    {
        $sql = 'SELECT pe.id, pe.fecha, e.estadofactura AS estado, COALESCE(calcularTotal(pe.id), CAST(0 AS DECIMAL(8, 2))) AS total
                FROM pedidos pe
                INNER JOIN estadofactura e ON pe.estado = e.id
                WHERE pe.id_cliente = ?
                ORDER BY pe.id DESC';
        $params = array($id_cliente);
        return Database::getRows($sql, $params);
    }

    public function readDetailHistorial($id_pedido)
    {
        $sql = 'SELECT d.id, d.id_pedido, p.producto, m.marca, d.preciounitario, d.cantidad, (d.preciounitario * d.cantidad) AS subtotal, p.imagen
                FROM detallepedidos d
                INNER JOIN productos p ON d.id_producto = p.id
                INNER JOIN marcas m ON m.id = p.id_marca
                WHERE d.id_pedido = ?
                ORDER BY subtotal DESC';
        $params = array($id_pedido);
        return Database::getRows($sql, $params);
    }

    public function verificarPedido($idPedido)
    {
        $sql = 'SELECT pe.id
                FROM pedidos pe
                WHERE pe.id = ?';
        $params = array($idPedido);
        return Database::getRows($sql, $params);
    }

    // Método para finalizar un pedido por parte del cliente.
    public function finishOrder()
    {
        $this->estado = 2;
        $sql = 'UPDATE pedidos
                SET estado = ?
                WHERE id = ?';
        $params = array($this->estado, $_SESSION['id_pedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para actualizar la cantidad de un producto agregado al carrito de compras.
    public function updateDetail()
    {
        $sql = 'UPDATE detallepedidos
                SET cantidad = ?
                WHERE id = ? AND id_pedido = ?';
        $params = array($this->cantidad, $this->id_detalle, $_SESSION['id_pedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un producto que se encuentra en el carrito de compras.
    public function deleteDetail()
    {
        $sql = 'DELETE FROM detallepedidos
                WHERE id = ? AND id_pedido = ?';
        $params = array($this->id_detalle, $_SESSION['id_pedido']);
        return Database::executeRow($sql, $params);
    }

    // Método para restar el stock de un producto.
    public function restarStock()
    {
        $newstock = $this->cantidadStock - $this->cantidad;
        $sql = 'UPDATE productos SET cantidad = ? WHERE id = ?';
        $params = array($newstock, $this->producto);
        return Database::executeRow($sql, $params);
    }

    // Método para restaurar el stock de un producto.
    public function restoreStock()
    {
        $newstock = $this->cantidad + $this->cantidadStock;
        $sql = 'UPDATE productos SET cantidad = ? WHERE id = ?';
        $params = array($newstock, $this->producto);
        return Database::executeRow($sql, $params);
    }

    // Método para actualizar el stock de un producto.
    public function updateStock($cantidad)
    {
        $sql = 'UPDATE productos SET cantidad = ? WHERE id = ?';
        $params = array($cantidad, $this->producto);
        return Database::executeRow($sql, $params);
    }

    // Método para actualizar la cantidad de un producto en un pedido.
    public function updateOrderStock($cantidad)
    {
        $sql = 'UPDATE detallepedidos SET cantidad = ? WHERE id = ? AND id_pedido = ?';
        $params = array($cantidad, $this->id_detalle, $_SESSION['id_pedido']);
        return Database::executeRow($sql, $params);
    }
}
