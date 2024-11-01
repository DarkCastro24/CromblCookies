
<?php
/*
*	Clase para manejar la tabla productos de la base de datos. Es clase hija de Validator.
*/
class Productos extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $categoria = null;
    private $estado = null;
    private $marca = null;
    private $producto = null;
    private $precio = null;
    private $descripcion = null;
    private $imagen = null;
    private $accion = null;
    private $ruta = '../../../resources/img/productos/';

    /*
    *   Métodos para asignar valores a los atributos.
    */
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

    public function setCategoria($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->categoria = $value;
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

    public function setMarca($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->marca = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setProducto($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setPrecio($value)
    {
        if ($this->validateMoney($value)) {
            $this->precio = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDescripcion($value)
    {
        if ($this->validateAlphanumeric($value, 1, 150)) {
            $this->descripcion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setImagen($file)
    {
        if ($this->validateImageFile($file, 500, 500)) {
            $this->imagen = $this->getImageName();
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function getProducto()
    {
        return $this->producto;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getRuta()
    {
        return $this->ruta;
    }

    // Función para realizar la búsqueda filtrada
    public function searchRows($value)
    {
        $sql = 'SELECT p.id AS id_producto, c.categoria AS categoria_producto, p.estado AS estado_producto, 
                       m.marca AS marca_producto, p.producto AS nombre_producto, p.precio AS precio_producto, 
                       p.descripcion AS descripcion_producto, p.imagen AS imagen_producto
                FROM productos p
                INNER JOIN categorias c ON c.id = p.id_categoria
                INNER JOIN marcas m ON m.id = p.id_marca
                WHERE p.producto ILIKE ? OR c.categoria ILIKE ?
                ORDER BY p.estado DESC';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    // Función para ingresar un producto en la base de datos
    public function createRow()
    {
        $sql = 'INSERT INTO productos (id, id_categoria, estado, id_marca, producto, precio, descripcion, imagen, cantidad)
                VALUES (DEFAULT, ?, TRUE, ?, ?, ?, ?, ?, 10)';
        $params = array($this->categoria, $this->marca, $this->producto, $this->precio, $this->descripcion, $this->imagen);
        return Database::executeRow($sql, $params);
    }

    // Función para cargar todos los datos de la tabla productos
    public function readAll()
    {
        $sql = 'SELECT p.id AS id_producto, c.categoria AS categoria_producto, p.estado AS estado_producto, 
                       m.marca AS marca_producto, p.producto AS nombre_producto, p.precio AS precio_producto, 
                       p.descripcion AS descripcion_producto, p.imagen AS imagen_producto
                FROM productos p
                INNER JOIN categorias c ON c.id = p.id_categoria
                INNER JOIN marcas m ON m.id = p.id_marca
                ORDER BY p.estado DESC';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Función para cargar las categorías
    public function readCategoria()
    {
        $sql = 'SELECT id, categoria FROM categorias ORDER BY categoria';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Función para cargar las marcas
    public function readMarca()
    {
        $sql = 'SELECT id, marca FROM marcas ORDER BY marca';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Función para cargar los datos de un producto específico
    public function readOne()
    {
        $sql = 'SELECT p.id AS id_producto, c.categoria AS categoria_producto, p.estado AS estado_producto, 
                       m.marca AS marca_producto, p.producto AS nombre_producto, p.cantidad AS cantidad_producto, 
                       p.precio AS precio_producto, p.descripcion AS descripcion_producto, 
                       p.imagen AS imagen_producto
                FROM productos p
                INNER JOIN categorias c ON c.id = p.id_categoria
                INNER JOIN marcas m ON m.id = p.id_marca
                WHERE p.id = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Función para actualizar un producto
    public function updateRow($current_image)
    {
        // Verificar si se ha proporcionado una nueva imagen
        if ($this->imagen) {
            // Eliminar la imagen anterior si se ha proporcionado una nueva
            $this->deleteFile($this->getRuta(), $current_image);

            // Consulta para actualizar todos los campos, incluyendo la imagen
            $sql = 'UPDATE productos
                    SET producto = ?, precio = ?, descripcion = ?, imagen = ?, id_marca = ?, id_categoria = ?
                    WHERE id = ?';
            $params = array($this->producto, $this->precio, $this->descripcion, $this->imagen, $this->marca, $this->categoria, $this->id);
        } else {
            // Consulta para actualizar todos los campos, excepto la imagen
            $sql = 'UPDATE productos
                    SET producto = ?, precio = ?, descripcion = ?, id_marca = ?, id_categoria = ?
                    WHERE id = ?';
            $params = array($this->producto, $this->precio, $this->descripcion, $this->marca, $this->categoria, $this->id);
        }

        return Database::executeRow($sql, $params);
    }


    // Función para eliminar (desactivar) un producto
    public function deleteRow()
    {
        $sql = 'UPDATE productos SET estado = ? WHERE id = ?';
        $params = array($this->accion, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Funciones para reportes

    // Función para reporte de los clientes con más productos adquiridos
    public function comprasClientes()
    {
        $sql = "SELECT c.correo_electronico AS correo_cliente, c.nombres || ' ' || c.apellidos AS nombre_cliente, 
                       c.dui AS dui_cliente, SUM(de.cantidad) AS cantidad_productos
                FROM facturas f
                INNER JOIN clientes c ON c.id = f.id_cliente
                INNER JOIN detallepedidos de ON de.id_factura = f.id
                WHERE f.estado = 2
                GROUP BY c.correo_electronico, c.nombres, c.apellidos, c.dui
                ORDER BY cantidad_productos DESC";
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Función para gráfico de venta de productos por categoría
    public function categoriasVentas()
    {
        $sql = 'SELECT c.categoria AS categoria_producto, SUM(d.cantidad) AS cantidad_productos
                FROM facturas f
                INNER JOIN detallepedidos d ON d.id_factura = f.id
                INNER JOIN productos p ON p.id = d.id_producto
                INNER JOIN categorias c ON c.id = p.id_categoria
                WHERE f.estado = 2
                GROUP BY c.categoria
                ORDER BY cantidad_productos DESC';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
