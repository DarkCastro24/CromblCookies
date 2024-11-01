<?php

class Clientes extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombres = null;
    private $apellidos = null;
    private $estado = null;
    private $dui = null;
    private $correo = null;
    private $clave = null;
    private $accion = null;

    //METODOS PARA ASIGNAR EL VALOR A LOS ATRIBUTOS
    public function setId($value)
    {
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

    public function setNombres($value)
    {
        if ($this->validateAlphanumeric($value, 1, 40)) {
            $this->nombres = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAccion($value)
    {
        $this->accion = $value;
        return true;
    }

    public function setApellidos($value)
    {
        if ($this->validateAlphanumeric($value, 1, 40)) {
            $this->apellidos = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDui($value)
    {
        if ($this->validateDUI($value)) {
            $this->dui = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreo($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setClave($value)
    {
        if ($this->validatePassword($value)) {
            $this->clave = $value;
            return true;
        } else {
            return false;
        }
    }

    // METODOS GET PARA OBTENER EL VALOR DE LAS VARIABLES 
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombres;
    }

    public function getApellido()
    {
        return $this->apellidos;
    }

    public function getDui()
    {
        return $this->dui;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getClave()
    {
        return $this->clave;
    }

    // Método para buscar clientes
    public function searchRows($value)
    {
        $sql = 'SELECT c.id AS id_cliente, c.estado AS estado_cliente, c.nombres AS nombres_cliente, 
                c.apellidos AS apellidos_cliente, c.dui AS dui_cliente, c.correo_electronico AS correo_cliente
                FROM clientes c
                WHERE c.correo_electronico ILIKE ? OR c.dui ILIKE ?
                ORDER BY c.nombres';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    // Método para registrar a un cliente
    public function createRow()
    {
        // Se encripta la clave por medio del algoritmo bcrypt que genera un string de 60 caracteres.
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO clientes (id, nombres, apellidos, dui, correo_electronico, clave, fecharegistro, estado) 
                VALUES (DEFAULT, ?, ?, ?, ?, ?, DEFAULT, DEFAULT)';
        $params = array($this->nombres, $this->apellidos, $this->dui, $this->correo, $hash);
        return Database::executeRow($sql, $params);
    }

    // Método para cargar todos los registros de clientes
    public function readAll()
    {
        $sql = 'SELECT c.id AS id_cliente, c.estado AS estado_cliente, c.nombres AS nombres_cliente, 
                    c.apellidos AS apellidos_cliente, c.dui AS dui_cliente, c.correo_electronico AS correo_cliente
                FROM clientes c
                ORDER BY c.nombres';
        $params = null;
        return Database::getRows($sql, $params);
    }


    // Método para cargar los datos de un cliente
    public function readOne()
    {
        $sql = 'SELECT c.id AS id_cliente, c.nombres AS nombres_cliente, c.apellidos AS apellidos_cliente, 
                    c.dui AS dui_cliente, c.correo_electronico AS correo_cliente, c.clave AS clave_cliente
                FROM clientes c
                WHERE c.id = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }


    // Método para actualizar los datos de un cliente
    public function updateRow()
    {
        $sql = 'UPDATE clientes 
                SET nombres = ?, apellidos = ?, correo_electronico = ? 
                WHERE id = ?';
        $params = array($this->nombres, $this->apellidos, $this->correo, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar (desactivar) un cliente
    public function deleteRow()
    {
        $sql = 'UPDATE clientes 
                SET estado = ? 
                WHERE id = ?';
        $params = array($this->accion, $this->id);
        return Database::executeRow($sql, $params);
    }


    // Método para obtener el correo del cliente para el inicio de sesión
    public function checkUser($correo)
    {
        $sql = 'SELECT c.id AS id_cliente, c.estado AS estado_cliente, c.nombres AS nombres_cliente 
                FROM clientes c 
                WHERE c.correo_electronico = ?';
        $params = array($correo);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['id_cliente'];
            $this->estado = $data['estado_cliente'];
            $this->correo = $correo;
            return true;
        } else {
            return false;
        }
    }

    // Método para verificar si el usuario está activo
    public function checkState($usuario)
    {
        $sql = 'SELECT c.id AS id_cliente 
                FROM clientes c 
                WHERE c.correo_electronico = ? AND c.estado = TRUE';
        $params = array($usuario);
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }

    // Método para obtener la contraseña del cliente para el inicio de sesión
    public function checkPassword($password)
    {
        $sql = 'SELECT c.clave AS clave_cliente 
                FROM clientes c 
                WHERE c.id = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        $this->clave = $password;
        if (password_verify($password, $data['clave_cliente'])) {
            return true;
        } else {
            return false;
        }
    }

    // Método para cargar las facturas de un cliente
    public function cargarFacturas()
    {
        $sql = 'SELECT CONCAT(c.categoria, \' \', p.producto) AS producto, 
                    cl.id AS id_cliente, 
                    d.preciounitario AS precio_unitario, 
                    d.cantidad AS cantidad_producto
                FROM detallepedidos d
                INNER JOIN pedidos f ON d.id_pedido = f.id
                INNER JOIN clientes cl ON f.id_cliente = cl.id
                INNER JOIN productos p ON d.id_producto = p.id
                INNER JOIN categorias c ON p.id_categoria = c.id
                WHERE cl.id = ? AND f.estado = 2 AND f.fecha = CURRENT_DATE';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
}
