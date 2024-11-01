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
            // Método para cargar todos los datos
            case 'readAll': 
                if ($result['dataset'] = $categorias->readAll()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = 'Error en la base de datos al cargar las categorías.';
                    } else {
                        $result['exception'] = 'No hay categorías registradas actualmente.';
                    }
                }
                break;

            // Método para ejecutar la búsqueda filtrada
            case 'search': 
                $_POST = $categorias->validateForm($_POST);
                if ($_POST['search'] != '') {
                    if ($result['dataset'] = $categorias->searchRows($_POST['search'])) {
                        $result['status'] = 1;
                        $rows = count($result['dataset']);
                        $result['message'] = ($rows > 1) ? 'Se encontraron ' . $rows . ' coincidencias.' : 'Solo existe una coincidencia.';
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = 'Error al buscar las categorías.';
                        } else {
                            $result['exception'] = 'No se encontraron categorías con ese criterio de búsqueda.';
                        }
                    }
                } else {
                    $result['exception'] = 'Por favor, ingrese un término de búsqueda.';
                }
                break;

            // Método para ingresar registros en la base de datos
            case 'create': 
                $_POST = $categorias->validateForm($_POST);   
                if ($categorias->setCategoria($_POST['txtCategoria'])) {
                    if ($categorias->setDescripcion($_POST['txtDescripcion'])) { // Ahora permitirá comas
                        if (is_uploaded_file($_FILES['archivo_producto']['tmp_name'])) {
                            if ($categorias->setImagen($_FILES['archivo_producto'])) {
                                if ($categorias->createRow()) {
                                    $result['status'] = 1;
                                    if ($categorias->saveFile($_FILES['archivo_producto'], $categorias->getRuta(), $categorias->getImagen())) {
                                        $result['message'] = 'Categoría registrada correctamente.';
                                    } else {
                                        $result['message'] = 'Categoría registrada, pero hubo un problema al guardar la imagen.';
                                    }
                                } else {
                                    $result['exception'] = 'Ocurrió un error al intentar registrar la categoría.';
                                } 
                            } else {
                                $result['exception'] = $categorias->getImageError();
                            }
                        } else {
                            $result['exception'] = 'Por favor, seleccione una imagen para la categoría.';
                        }                                          
                    } else {
                        $result['exception'] = 'La descripción ingresada no es válida. Recuerde que solo se permiten letras, espacios y comas.';
                    }     
                } else {
                    $result['exception'] = 'El nombre de la categoría no es válido.';
                }   
                break;
            

            // Método para cargar los datos de un registro
            case 'readOne':
                if ($categorias->setId($_POST['txtId'])) {
                    if ($result['dataset'] = $categorias->readOne()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = 'Error al intentar cargar los datos de la categoría.';
                        } else {
                            $result['exception'] = 'La categoría solicitada no existe.';
                        }
                    }
                } else {
                    $result['exception'] = 'El identificador proporcionado no es válido.';
                }
                break;

            // Método para modificar datos de un registro
            case 'update': 
                $_POST = $categorias->validateForm($_POST);
                if ($categorias->setId($_POST['auxId'])) {
                    if ($data = $categorias->readOne()) {
                        if ($categorias->setCategoria($_POST['txtCategoria'])) {
                            if ($categorias->setDescripcion($_POST['txtDescripcion'])) {
                                if (is_uploaded_file($_FILES['archivo_producto']['tmp_name'])) {
                                    if ($categorias->setImagen($_FILES['archivo_producto'])) {
                                        if ($categorias->updateRow($data['imagen'])) {
                                            $result['status'] = 1;
                                            if ($categorias->saveFile($_FILES['archivo_producto'], $categorias->getRuta(), $categorias->getImagen())) {
                                                $result['message'] = 'Categoría modificada correctamente.';
                                            } else {
                                                $result['message'] = 'Categoría modificada, pero la imagen no pudo ser actualizada.';
                                            }
                                        } else {
                                            $result['exception'] = 'Ocurrió un error al intentar actualizar la categoría.';
                                        }
                                    } else {
                                        $result['exception'] = $categorias->getImageError();
                                    }
                                } else {
                                    if ($categorias->updateRow($data['imagen'])) {
                                        $result['status'] = 1;
                                        $result['message'] = 'Categoría modificada correctamente.';
                                    } else {
                                        $result['exception'] = 'Ocurrió un error al intentar actualizar la categoría.';
                                    }
                                }
                            } else {
                                $result['exception'] = 'La descripción ingresada no es válida.';
                            }
                        } else {
                            $result['exception'] = 'El nombre de la categoría ingresado no es válido.';
                        }
                    } else {
                        $result['exception'] = 'La categoría solicitada no existe.';
                    }
                } else {
                    $result['exception'] = 'El identificador proporcionado no es válido.';
                }
                break;

            default: 
                $result['exception'] = 'La acción solicitada no está disponible.';
        }
        header('content-type: application/json; charset=utf-8');
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado, por favor inicie sesión.'));
    }
} else {
    print(json_encode('El recurso solicitado no está disponible.'));
}

