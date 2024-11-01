<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/clientes.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    session_start();
    // Se instancia la clase correspondiente.
    $clientes = new Clientes;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    if (isset($_SESSION['idusuario'])) {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            // Caso para cargar todos datos de la tabla
            case 'readAll': 
                // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $clientes->readAll()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay clientes registrados';
                    }
                }
                break;
            // Caso para realizar busqueda filtrada
            case 'search': 
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $clientes->validateForm($_POST);
                // Validamos si el contenido del search no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion del modelo 
                    if ($result['dataset'] = $clientes->searchRows($_POST['search'])) {
                        $result['status'] = 1;
                        $rows = count($result['dataset']);
                        // Comparamos el numero de filas obtenidas
                        if ($rows > 1) {
                            $result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
                        } else {
                            $result['message'] = 'Solo existe una coincidencia';
                        }
                    } else {
                        // Se ejecuta si existe algun error en la base de datos 
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
            // Caso para ingresar registros a la base de datos
            case 'create': 
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $clientes->validateForm($_POST);
                if ($clientes->setNombres($_POST['txtNombre'])) {
                    if ($clientes->setApellidos($_POST['txtApellido'])) {
                        if ($clientes->setDui($_POST['txtDui'])) {
                            if ($clientes->setCorreo($_POST['txtCorreo'])) {
                                if ($clientes->setClave($_POST['txtClave'])) {
                                    if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                        if ($clientes->setClave($_POST['txtClave'])) {
                                            // Ejecutamos la funcion del modelo 
                                            if ($clientes->createRow()) {
                                                $result['status'] = 1;
                                                $result['message'] = 'Cliente registrado correctamente';
                                            } else {
                                                $result['exception'] = Database::getException();;           
                                            } 
                                        } else {
                                            $result['exception'] = $clientes->getPasswordError();
                                        }
                                    } else {
                                        $result['exception'] = 'Claves diferentes';
                                    }                                                               
                                } else {
                                    $result['exception'] = 'Clave incorrecta';
                                }                                                                                
                            } else {
                                $result['exception'] = 'Correo incorrecto';
                            }                                                                                    
                        } else {
                            $result['exception'] = 'Dui incorrecto';
                        }                                                                                     
                    } else {
                        $result['exception'] = 'Apellido incorrecto';
                    }
                } else {
                    $result['exception'] = 'Nombre incorrecto';
                }
                break;
                
            // Caso para cargar los datos de un registro en especifico
            case 'readOne': 
                // Obtenemos el valor del id
                if ($clientes->setId($_POST['txtId'])) {
                    // Ejecutamos la funcion del modelo 
                    if ($result['dataset'] = $clientes->readOne()) {
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
            // Caso para modificar registros en la base de datos
            case 'update': 
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $clientes->validateForm($_POST);
                if ($clientes->setId($_POST['auxId'])) {
                    if ($clientes->setNombres($_POST['txtNombre'])) {
                        if ($clientes->setApellidos($_POST['txtApellido'])) {
                            if ($clientes->setCorreo($_POST['txtCorreo'])) {
                                if ($clientes->updateRow()) {
                                    $result['status'] = 1;
                                    $result['message'] = 'Cliente modificado correctamente';
                                } else {
                                    $result['exception'] = Database::getException();
                                }                        
                            } else {
                                $result['exception'] = 'Correo incorrecto';
                            }                                                                                                                                                            
                        } else {
                            $result['exception'] = 'Apellido incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Nombre incorrecto';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            // Caso para eliminar registros en la base de datos
            case 'delete':  
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $clientes->validateForm($_POST);
                // Obtenemos el valor del id
                if ($clientes->setId($_POST['txtId'])) {
                    if ($clientes->setAccion($_POST['txtAccion'])) {
                        if ($data = $clientes->readOne()) {
                            // Ejecutamos la funcion del modelo 
                            if ($clientes->deleteRow()) {
                                $result['status'] = 1;
                                $result['message'] = 'Estado actualizado correctamente';
                            } else {
                                $result['exception'] = Database::getException();
                            }
                        } else {
                            $result['exception'] = 'Cliente inexistente';
                        }
                    } else {
                        $result['exception'] = 'Accion incorrecta';
                    }
                } else {
                    $result['exception'] = 'Cliente incorrecto';
                }
                // Si el caso no coincide con ninguno de los casos anteriores se muestra el siguiente mensaje
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
