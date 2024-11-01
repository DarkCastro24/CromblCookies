<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/categorias.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    session_start();
    $categorias = new Categorias;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    if (isset($_SESSION['idusuario'])) {
        switch ($_GET['action']) {
            // Metodo para cargar todos los datos
            case 'readAll': 
                // Ejecutamos el metodo para cargar los datos 
                if ($result['dataset'] = $categorias->readAll()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay categorias registradas';
                    }
                }
                break;
                // Metodo para ejecutar la busqueda filtrada
                case 'search': 
                    $_POST = $categorias->validateForm($_POST);
                    if ($_POST['search'] != '') {
                        // Ejecutamos la funcion que realiza la busqueda filtrada
                        if ($result['dataset'] = $categorias->searchRows($_POST['search'])) {
                            $result['status'] = 1;
                            // Obtenemos el numero de filas retornadas por la consulta 
                            $rows = count($result['dataset']);
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
                    // Caso para ingresar registros en la base de datos
                    case 'create': 
                        // Obtenemos el form con los inputs para obtener los datos
                        $_POST = $categorias->validateForm($_POST);   
                        if ($categorias->setCategoria($_POST['txtCategoria'])) {
                            if ($categorias->setDescripcion($_POST['txtDescripcion'])) {
                                if (is_uploaded_file($_FILES['archivo_producto']['tmp_name'])) {
                                    if ($categorias->setImagen($_FILES['archivo_producto'])) {
                                        // Ejecutamos la funcion del modelo 
                                        if ($categorias->createRow()) {
                                            $result['status'] = 1;
                                            // Guardamos la imagen dentro de la carpeta del proyecto
                                            if ($categorias->saveFile($_FILES['archivo_producto'], $categorias->getRuta(), $categorias->getImagen())) {
                                                $result['message'] = 'Categoria registrada correctamente';
                                            } else {
                                                $result['message'] = 'Categoria guardada pero sin imagen';
                                            }
                                        } else {
                                            $result['exception'] = Database::getException();;
                                        } 
                                    } else {
                                        $result['exception'] = $categorias->getImageError();
                                    }
                                } else {
                                    $result['exception'] = 'Seleccione una imagen';
                                }                                          
                            } else {
                                $result['exception'] = 'Descripcion incorrecta';
                            }     
                        } else {
                            $result['exception'] = 'Categoría incorrecta';
                        }   
                    break;
                    // Caso para cargar los datos de un registro 
                    case 'readOne':
                        if ($categorias->setId($_POST['txtId'])) {
                            // Ejecutamos la funcion del modelo
                            if ($result['dataset'] = $categorias->readOne()) {
                                $result['status'] = 1;
                            } else {
                                if (Database::getException()) {
                                    $result['exception'] = Database::getException();
                                } else {
                                    $result['exception'] = 'Usuario inexistente';
                                }
                            }
                        } else {
                            $result['exception'] = 'Usuario incorrecto';
                        }
                        break;
                    // Caso para modificar datos de un registro
                    case 'update': 
                        // Obtenemos el form con los inputs para obtener los datos
                        $_POST = $categorias->validateForm($_POST);
                        if ($categorias->setId($_POST['auxId'])) {
                            if ($data = $categorias->readOne()) {
                                if ($categorias->setCategoria($_POST['txtCategoria'])) {
                                    if ($categorias->setDescripcion($_POST['txtDescripcion'])) {
                                        if (is_uploaded_file($_FILES['archivo_producto']['tmp_name'])) {
                                            if ($categorias->setImagen($_FILES['archivo_producto'])) {
                                                if ($categorias->updateRow($data['imagen'])) {
                                                    $result['status'] = 1;
                                                    // Guardamos la imagen dentro de la carpeta de la base de datos
                                                    if ($categorias->saveFile($_FILES['archivo_producto'], $categorias->getRuta(), $categorias->getImagen())) {
                                                        $result['message'] = 'Categoria modificada correctamente';
                                                    } else {
                                                        $result['message'] = 'Categoria modificada pero no se guardó la imagen';
                                                    }
                                                } else {
                                                    $result['exception'] = Database::getException();
                                                }
                                            } else {
                                                $result['exception'] = $categorias->getImageError();
                                            }
                                        } else {
                                            // Ejecutamos la funcion guardando la imagen 
                                            if ($categorias->updateRow($data['imagen'])) {
                                                $result['status'] = 1;
                                                $result['message'] = 'Producto modificado correctamente';
                                            } else {
                                                $result['exception'] = Database::getException();
                                            }
                                        }
                                    } else {
                                        $result['exception'] = 'Descripcion incorrecta';
                                    }
                                } else {
                                    $result['exception'] = 'Categoria incorrecta';
                                }
                            } else {
                                $result['exception'] = 'Producto no existe';
                            }
                        } else {
                            $result['exception'] = 'Id incorrecto';
                        }
                    break;
                default: 
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        header('content-type: application/json; charset=utf-8');
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
