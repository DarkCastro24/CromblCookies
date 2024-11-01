<?php
ini_set('display_errors', 0);
error_reporting(0);
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/productos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $producto = new Productos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idusuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            // Caso para cargar todos los datos de la tabla
            case 'readAll':
                // Ejecutamos la funcion del modelo 
                if ($result['dataset'] = $producto->readAll()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay productos registrados';
                    }
                }
                break;
            // Caso para cargar todos los datos de la tabla
            case 'readCategoria':
                // Ejecutamos la funcion del modelo 
                if ($result['dataset'] = $producto->readCategoria()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay productos registrados';
                    }
                }
                break;
            // Caso para cargar todos los datos de la tabla
            case 'readMarca':
                // Ejecutamos la funcion del modelo 
                if ($result['dataset'] = $producto->readMarca()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay productos registrados';
                    }
                }
                break;
            // Caso para realizar la busqueda filtrada
            case 'search':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $producto->validateForm($_POST);
                // Validamos si el contenido del search no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion del modelo 
                    if ($result['dataset'] = $producto->searchRows($_POST['search'])) {
                        $result['status'] = 1;
                        $rows = count($result['dataset']);
                        // Comparamos el numero de filas obtenidas
                        if ($rows > 1) {
                            $result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
                        } else {
                            $result['message'] = 'Solo existe una coincidencia';
                        }
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'No hay coincidencias';
                        }
                    }
                } else {
                    $result['exception'] = 'Ingrese un valor para buscar';
                }
                break;
            // Caso para registrar un producto en la base de datos
            case 'create':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $producto->validateForm($_POST);   
                    if ($producto->setProducto($_POST['txtProducto'])) {
                        if ($producto->setPrecio($_POST['txtPrecio'])) {
                            if ($producto->setDescripcion($_POST['txtDescripcion'])) {
                                if (isset($_POST['cmbCategoria'])) {
                                    if ($producto->setCategoria($_POST['cmbCategoria'])) { 
                                        if (isset($_POST['cmbMarca'])) {
                                            if ($producto->setMarca($_POST['cmbMarca'])) {
                                                if (is_uploaded_file($_FILES['archivo_producto']['tmp_name'])) {
                                                    if ($producto->setImagen($_FILES['archivo_producto'])) {
                                                        // Ejecutamos la funcion para ingresar los datos 
                                                        if ($producto->createRow()) {
                                                            $result['status'] = 1;
                                                            // Guardamos la imagen dentro de la carpeta del proyecto
                                                            if ($producto->saveFile($_FILES['archivo_producto'], $producto->getRuta(), $producto->getImagen())) {
                                                                $result['message'] = 'Producto creado correctamente';
                                                            } else {
                                                                $result['message'] = 'Producto creado pero no se guardó la imagen';
                                                            }
                                                        } else {
                                                            $result['exception'] = Database::getException();;
                                                        }
                                                    } else {
                                                        $result['exception'] = $producto->getImageError();
                                                    }
                                                } else {
                                                    $result['exception'] = 'Seleccione una imagen';
                                                }
                                            } else {
                                                $result['exception'] = 'Marca incorrecta';
                                            }
                                        } else {
                                            $result['exception'] = 'Seleccione una marca';
                                        }     
                                    } else {
                                        $result['exception'] = 'Categoría incorrecta';
                                    }
                                } else {
                                    $result['exception'] = 'Seleccione una categoría';
                                }
                            } else {
                                 $result['exception'] = 'Descripcion incorrecta';
                            }
                        } else {
                            $result['exception'] = 'Precio incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Nombre producto incorrecto';
                    }     
                break;
            // Caso para leer en contenido de un solo registro
            case 'readOne':
                if (isset($_POST['txtId']) && $producto->setId($_POST['txtId'])) {
                    // Ejecutamos la función del modelo 
                    if ($result['dataset'] = $producto->readOne()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'Producto inexistente';
                        }
                    }
                } else {
                    $result['exception'] = 'Producto incorrecto';
                }
                break;                      
            // Caso para actualizar los datos de un registro
            case 'update':
                // Validar el formulario
                $_POST = $producto->validateForm($_POST);
            
                // Comprobar si el ID del producto se ha establecido
                if (isset($_POST['auxId']) && $producto->setId($_POST['auxId'])) {
                    if ($data = $producto->readOne()) {
                        // Validar los demás campos del formulario
                        if ($producto->setProducto($_POST['txtProducto'])) {
                            if ($producto->setPrecio($_POST['txtPrecio'])) {
                                if ($producto->setDescripcion($_POST['txtDescripcion'])) {
                                    if (isset($_POST['cmbCategoria']) && $producto->setCategoria($_POST['cmbCategoria'])) {
                                        if (isset($_POST['cmbMarca']) && $producto->setMarca($_POST['cmbMarca'])) {
            
                                            // Verificar si se ha subido una nueva imagen
                                            if (is_uploaded_file($_FILES['archivo_producto']['tmp_name'])) {
                                                // Si se subió una imagen, establecerla y actualizar el producto.
                                                if ($producto->setImagen($_FILES['archivo_producto'])) {
                                                    if ($producto->updateRow($data['imagen'])) {
                                                        $result['status'] = 1;
                                                        if ($producto->saveFile($_FILES['archivo_producto'], $producto->getRuta(), $producto->getImagen())) {
                                                            $result['message'] = 'Producto modificado correctamente';
                                                        } else {
                                                            $result['message'] = 'Producto modificado, pero no se guardó la nueva imagen';
                                                        }
                                                    } else {
                                                        $result['exception'] = Database::getException();
                                                    }
                                                } else {
                                                    $result['exception'] = $producto->getImageError();
                                                }
                                            } else {
                                                // Si no se subió una imagen, mantener la imagen existente.
                                                if ($producto->updateRow($data['imagen'])) {
                                                    $result['status'] = 1;
                                                    $result['message'] = 'Producto modificado correctamente';
                                                } else {
                                                    $result['exception'] = Database::getException();
                                                }
                                            }
                                        } else {
                                            $result['exception'] = 'Marca incorrecta';
                                        }
                                    } else {
                                        $result['exception'] = 'Categoría incorrecta';
                                    }
                                } else {
                                    $result['exception'] = 'Descripción incorrecta';
                                }
                            } else {
                                $result['exception'] = 'Precio incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Nombre incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Producto inexistente';
                    }
                } else {
                    $result['exception'] = 'Producto incorrecto';
                }
                break;                   
            // Caso para eliminar un registro de la base de datos
            case 'delete':
                // Obtenemos el valor del id
                if ($producto->setId($_POST['txtId'])) {
                    if ($producto->setAccion($_POST['txtAccion'])) {
                        if ($data = $producto->readOne()) {
                            // Ejecutamos la funcion del modelo
                            if ($producto->deleteRow()) {
                                $result['status'] = 1;
                                $result['message'] = 'Estado modificado correctamente';
                            } else {
                                $result['exception'] = Database::getException();
                            }
                        } else {
                            $result['exception'] = 'Producto inexistente';
                        }
                    } else {
                        $result['exception'] = 'Producto incorrecto';
                    }
                } else {
                    $result['exception'] = 'Producto incorrecto';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
