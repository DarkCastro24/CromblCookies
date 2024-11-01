<?php
/*
*	Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/

class Categorias extends Validator
{
    private $id = null;
    private $categoria = null;
    private $descripcion = null;
    private $imagen = null;
    private $ruta = '../../../resources/img/categorias/';

    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCategoria($value)
    {
        if ($this->validateAlphanumeric($value, 1, 40)) {
            $this->categoria = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDescripcion($value)
    {
        $this->descripcion = trim($value);

        if (preg_match('/^[a-zA-ZñÑáÁéÉíÍóÓúÚ\s,]{1,500}$/', $this->descripcion)) {
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

    public function getId()
    {
        return $this->id;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getRuta()
    {
        return $this->ruta;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    //metodo para cargar las categorias
    public function readAll()
    {
        $sql = 'SELECT id as id_categoria, categoria, descripcion, imagen, 
                    (SELECT COUNT(*) FROM productos WHERE productos.id_categoria = categorias.id) AS cantidad
                FROM categorias
                ORDER BY categoria ASC;';
        $params = null;
        return Database::getRows($sql, $params);
    }


    //metodo para buscar categorias
    public function searchRows($value)
    {
        $sql = 'SELECT id, categoria, descripcion, imagen 
                FROM categorias 
                WHERE categoria ILIKE ?
                ORDER BY categoria';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }


    //metodo para crear categorias
    public function createRow()
    {
        $sql = 'INSERT INTO categorias (id, categoria, descripcion, imagen)
                VALUES (DEFAULT, ?, ?, ?)';
        $params = array($this->categoria, $this->descripcion, $this->imagen);
        return Database::executeRow($sql, $params);
    }


    //metodo para cargar una categoria
    public function readOne()
    {
        $sql = 'SELECT id, categoria, descripcion, imagen 
                FROM categorias 
                WHERE id = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Funcion para actualizar un registro 
    public function updateRow($current_image)
    {
        // Verificamos si existe una imagen en la base de datos
        if ($this->imagen) {
            $this->deleteFile($this->getRuta(), $current_image);
        } else {
            $this->imagen = $current_image;
        }
        // Sentencia SQL
        $sql = 'UPDATE categorias 
                SET categoria = ?, descripcion = ?, imagen = ? 
                WHERE id = ?';
        // Envío de parámetros
        $params = array($this->categoria, $this->descripcion, $this->imagen, $this->id);
        return Database::executeRow($sql, $params);
    }


    /* FUNCION DEL PUBLIC */
    public function readProductosCategoria()
    {
        $sql = 'SELECT c.categoria, p.id AS id_producto, p.imagen, p.producto, p.descripcion, p.precio 
                FROM productos p 
                INNER JOIN categorias c ON p.id_categoria = c.id
                WHERE c.id = ? AND p.estado = TRUE
                ORDER BY p.producto';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
}
