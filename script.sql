/*
*   SCRIPT BASE DE DATOS PARA CromblCookies
*   FECHA: 04/10/2024
*/

-- SCRIPT PARA CREAR LA BASE DE DATOS 

-- Creación de la tabla categorias
CREATE TABLE categorias (
    id SERIAL PRIMARY KEY,
    categoria VARCHAR(32) NOT NULL,
    descripcion VARCHAR(150),
    imagen VARCHAR(150)
);

-- Creación de la tabla clientes
CREATE TABLE clientes (
    id SERIAL PRIMARY KEY,
    nombres VARCHAR(32) NOT NULL,
    apellidos VARCHAR(32) NOT NULL,
    dui CHAR(10) NOT NULL,
    correo_electronico VARCHAR(72) NOT NULL,
    clave VARCHAR(100) NOT NULL,
    fecharegistro DATE NULL DEFAULT CURRENT_DATE,
    estado BOOLEAN NOT NULL DEFAULT TRUE
);

-- Creación de la tabla direcciones
CREATE TABLE direcciones (
    id SERIAL PRIMARY KEY,
    id_cliente INT NOT NULL,
    direccion TEXT NOT NULL,
    codigo_postal VARCHAR(10),
    telefono_fijo VARCHAR(15),
    CONSTRAINT fk_cliente_direccion FOREIGN KEY (id_cliente) REFERENCES clientes (id)
);

-- Creación de la tabla estadofactura
CREATE TABLE estadofactura (
    id INT PRIMARY KEY,
    estadofactura VARCHAR(24) NOT NULL
);

-- Creación de la tabla facturas
CREATE TABLE pedidos(
    id SERIAL PRIMARY KEY,
    id_cliente INT NOT NULL,
    estado INT NOT NULL,
    fecha DATE NOT NULL DEFAULT CURRENT_DATE,
    CONSTRAINT fk_cliente_factura FOREIGN KEY (id_cliente) REFERENCES clientes (id)
);

-- Creación de la tabla marcas
CREATE TABLE marcas (
    id INT PRIMARY KEY,
    marca VARCHAR(255) NOT NULL
);

-- Creación de la tabla tipousuarios
CREATE TABLE tipousuarios (
    id INT PRIMARY KEY,
    tipousuario VARCHAR(255) NOT NULL
);

-- Creación de la tabla productos
CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    id_categoria INT NOT NULL,
    id_marca INT NOT NULL,
    producto VARCHAR(48) NOT NULL,
    precio DECIMAL(8, 2) NOT NULL,
    descripcion VARCHAR(108),
    imagen VARCHAR(150),
    cantidad INT NOT NULL,
    estado BOOLEAN NOT NULL,
    CONSTRAINT fk_categoria_producto FOREIGN KEY (id_categoria) REFERENCES categorias (id),
    CONSTRAINT fk_marca_producto FOREIGN KEY (id_marca) REFERENCES marcas (id)
);

-- Creación de la tabla detallepedidos
CREATE TABLE detallepedidos (
    id SERIAL PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    preciounitario DECIMAL(8,2) NOT NULL,
    cantidad INT NOT NULL,
    CONSTRAINT fk_pedido_detalle FOREIGN KEY (id_pedido) REFERENCES pedidos (id),
    CONSTRAINT fk_producto_detalle FOREIGN KEY (id_producto) REFERENCES productos (id)
);

-- Creación de la tabla usuarios
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    id_tipo INT NOT NULL,
    usuario VARCHAR(48) NOT NULL,
    clave VARCHAR(100) NOT NULL,
    correo_electronico VARCHAR(72) NOT NULL,
    fecharegistro DATE NOT NULL DEFAULT CURRENT_DATE,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    telefono VARCHAR(15),
    dui CHAR(10),
    CONSTRAINT fk_tipousuario_usuario FOREIGN KEY (id_tipo) REFERENCES tipousuarios (id)
);

-- Creación de la tabla valoraciones
CREATE TABLE valoraciones (
    id SERIAL PRIMARY KEY,
    id_detalle INT NOT NULL,
    calificacion_producto INT NOT NULL,
    comentario_producto VARCHAR(100),
    fecha_comentario DATE NOT NULL,
    estado_comentario BOOLEAN NOT NULL,
    CONSTRAINT fk_detalle_valoracion FOREIGN KEY (id_detalle) REFERENCES detallepedidos (id)
);

INSERT INTO tipousuarios VALUES (1, 'Root');
INSERT INTO tipousuarios VALUES (2, 'Administrador');

insert into marcas values (1,'CromblCookies');

-- SCRIPT PARA REALIZAR DE PRUEBAS 
SELECT * FROM tipousuarios
SELECT * FROM usuarios

SELECT id
FROM facturas
WHERE estado = 0 AND id_cliente = 1


SELECT * FROM clientes

SELECT * FROM pedidos

SELECT * FROM detallepedidos

INSERT INTO detallepedidos(id_pedido, id_producto, preciounitario, cantidad)
                VALUES(1, 2, (SELECT precio FROM productos p WHERE p.id = 2), 2)

delete from detallepedidos

SELECT * FROM marcas

SELECT * FROM categorias

SELECT * FROM productos 

SELECT c.categoria, p.id AS id_producto, p.imagen, p.producto, p.descripcion, p.precio 
                FROM productos p 
                INNER JOIN categorias c ON p.id_categoria = c.id
                WHERE c.id = 1 AND p.estado = TRUE
                ORDER BY p.producto

SELECT id, categoria, descripcion, imagen, 
       (SELECT COUNT(*) FROM productos WHERE productos.id_categoria = categorias.id) AS cantidad
FROM categorias
ORDER BY categoria ASC;



DELETE FROM productos 

INSERT INTO tipousuarios VALUES (1, 'ROOT');

INSERT INTO clientes(id, nombres, apellidos, dui, correo_electronico, clave, fecharegistro, estado) 
        VALUES (default, ?, ?, ?, ?, ?, default, default)


INSERT INTO usuarios (id, id_tipo, usuario, clave, correo_electronico, fecharegistro, estado, telefono, dui) 
                VALUES (DEFAULT, ?, ?, ?, ?, DEFAULT, DEFAULT, ?, ?)

INSERT INTO usuarios (id, id_tipo, usuario, clave, correo_electronico, fecharegistro, estado, telefono, dui)
VALUES (DEFAULT, 1, 'ejemploUsuario', 'contraseñaEncriptada', 'usuario@ejemplo.com', DEFAULT, DEFAULT, '5551234567', '1234567890');

DELETE FROM usuarios;

DELETE FROM clientes 

select * from usuarios

SELECT u.id, t.tipousuario as tipo, usuario, correo_electronico, telefono 
                FROM usuarios u
                INNER JOIN tipousuarios t ON t.id = u.id_tipo 
                

SELECT p.id AS id_producto, c.categoria AS categoria_producto, p.estado AS estado_producto, 
                       m.marca AS marca_producto, p.producto AS nombre_producto, p.cantidad AS cantidad_producto, 
                       p.precio AS precio_producto, p.descripcion AS descripcion_producto, 
                       p.imagen AS imagen_producto
                FROM productos p
                INNER JOIN categorias c ON c.id = p.id_categoria
                INNER JOIN marcas m ON m.id = p.id_marca
                WHERE p.id = ?


-- SCRIPT PARA ELIMINAR TODAS LAS TABLAS
DROP TABLE IF EXISTS valoraciones;
DROP TABLE IF EXISTS detallepedidos;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS tipousuarios;
DROP TABLE IF EXISTS marcas;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS estadofactura;
DROP TABLE IF EXISTS direcciones;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS categorias;