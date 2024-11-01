<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/facturas.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $facturas = new facturas();
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
    if (isset($_SESSION['idusuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
                // Caso para cargar todos datos de la tabla
            case 'readAll':
                // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $facturas->readAll()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay facturas registrados';
                    }
                }
                break;
                // Caso para cargar los datos de un registro en especifico
            case 'readOne':
                // Obtenemos el valor del id
                if ($facturas->setId($_POST['txtId'])) {
                    // Ejecutamos la funcion del modelo 
                    if ($result['dataset'] = $facturas->readOne()) {
                        $result['status'] = 1;
                    } else {
                        // Se ejecuta si existe algun error en la base de datos 
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'factura inexistente';
                        }
                    }
                } else {
                    $result['exception'] = 'factura incorrecta';
                }
                break;
                // Caso para modificar registros en la base de datos
            case 'update':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $facturas->validateForm($_POST);
                if ($facturas->setId($_POST['txtId'])) {
                    // Ejecutamos la funcion readOne para verificar si existe la factura
                    if ($facturas->readOne()) {
                        if ($facturas->setUsuario($_POST['cmbTipo'])) {
                            if ($facturas->setEstado($_POST['cmbEstado'])) {
                                // Ejecutamos la funcion del modelo
                                if ($facturas->updateRow()) {
                                    $result['status'] = 1;
                                    $result['message'] = 'Factura modificada correctamente';
                                } else {
                                    $result['exception'] = Database::getException();
                                }
                            } else {
                                $result['exception'] = 'Estado incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Estado incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Factura inexistente';
                    }
                } else {
                    $result['exception'] = 'Factura incorrecta';
                }
                break;
                // Caso para realizar busqueda filtrada
            case 'search':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $facturas->validateForm($_POST);
                // Validamos si el contenido del search no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion del modelo 
                    if ($result['dataset'] = $facturas->searchRows($_POST['search'])) {
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
                // Caso para cargar todos los datos de la tabla de manera parametrizada
            case 'readAllParam':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $facturas->validateForm($_POST);
                // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $facturas->cargarDatosParam($_POST['txtIdx'])) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No existen registros';
                    }
                }
                break;
                //Caso para obtener el total
            case 'getTotal':
                if ($facturas->setId($_POST['txtIdx'])) {
                    if ($result['dataset'] = $facturas->getTotalPrice()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = 'id incorrecto';
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
