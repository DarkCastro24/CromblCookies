<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/usuarios.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $usuario = new Usuarios;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idusuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            // Caso para cerrar sesion dentro del sistema 
            case 'logOut':
                // Ejecutamos el metodo para cerrar sesion
                unset($_SESSION['idusuario']);
                $result['status'] = 1;
                // Mostramos mensaje de confirmacion
                $result['message'] = 'Sesión eliminada correctamente';
                break;
            // Caso para cerrar sesion por inactividad del usuario
            case 'logOut2':
                // Ejecutamos el metodo para cerrar sesion
                unset($_SESSION['idusuario']);
                $result['status'] = 1;
                // Mostramos mensaje de confirmacion
                $result['message'] = 'La sesión ha expirado';
                break;     
            // Caso para cargar todos datos de la tabla
            case 'readAll':
                // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $usuario->readAll()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay usuarios registrados';
                    }
                }
                break;
            case 'readUserType':
                // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $usuario->readUserType()) {
                    $result['status'] = 1;
                } else {
                // Se ejecuta si existe algun error en la base de datos 
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No hay tipo de usuarios registrados';
                }
            }
            break;     
            // Caso para realizar busqueda filtrada
            case 'search':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $usuario->validateForm($_POST);
                // Validamos si el contenido del search no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion del modelo
                    if ($result['dataset'] = $usuario->searchRows($_POST['search'])) {
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
                $_POST = $usuario->validateForm($_POST);
                if ($usuario->setId($_POST['txtId'])) {
                    if ($usuario->setAlias($_POST['txtUsuario'])) {
                        if ($usuario->setCorreo($_POST['txtCorreo'])) {
                            if (isset($_POST['cmbTipo'])) {
                                if ($usuario->setTipo($_POST['cmbTipo'])) {
                                    // Comparamos que las claves sean iguales
                                    if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                        if ($usuario->setClave($_POST['txtClave'])) {
                                            if ($usuario->setDui($_POST['txtDui'])) {
                                                if ($usuario->setTelefono($_POST['txtTelefono'])) {
                                                    // Ejecutamos la funcion para agregar el usuario a la base
                                                    if ($usuario->createRow()) {
                                                        $result['status'] = 1;
                                                        $result['message'] = 'Usuario registrado correctamente';
                                                    } else {
                                                        // Se ejecuta si existe algun error en la base de datos 
                                                        $result['exception'] = Database::getException();;
                                                    }
                                                } else {
                                                    $result['exception'] = 'Telefono incorrecto';
                                                }  
                                            } else {
                                                $result['exception'] = 'Dui incorrecto';
                                            }
                                        } else {
                                            $result['exception'] = $usuario->getPasswordError();
                                        }
                                    } else {
                                        $result['exception'] = 'Claves diferentes';
                                    }
                                } else {
                                    $result['exception'] = 'Categoría incorrecta';
                                }
                            } else {
                                $result['exception'] = 'Seleccione una categoría';
                            }
                        } else {
                            $result['exception'] = 'correo incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Nombre incorrecto';
                    }
                } else {
                    $result['exception'] = 'Id incorrecto';
                }       
                break;
            // Caso para cargar los datos de un registro en especifico
            case 'readOne':
                // Obtenemos el valor del id 
                if ($usuario->setId($_POST['txtId'])) {
                    // Ejecutamos la funcion del modelo 
                    if ($result['dataset'] = $usuario->readOne()) {
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
                // Caso para modificar registros en la base de datos
            case 'update':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $usuario->validateForm($_POST);
                if ($usuario->setId($_POST['auxId'])) {
                    if ($usuario->readOne()) {
                        if ($usuario->setCorreo($_POST['txtCorreo'])) {
                            if ($usuario->setAlias($_POST['txtUsuario'])) {   
                                if ($usuario->setTelefono($_POST['txtTelefono'])) {   
                                    // Ejecutamos la funcion para actualizar al usuario
                                    if ($usuario->updateRow()) {
                                        $result['status'] = 1;
                                        $result['message'] = 'Usuario modificado correctamente';
                                    } else {
                                        $result['exception'] = Database::getException();
                                    }
                                } else {
                                    $result['exception'] = 'Telefono incorrecto';
                                } 
                            } else {
                                $result['exception'] = 'Usuario incorrecto';
                            } 
                        } else {
                            $result['exception'] = 'Correo incorrecto';
                        }  
                    } else {
                        $result['exception'] = 'Usuario inexistente';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            // Caso para eliminar registros en la base de datos
            case 'delete':
                // Verificamos si el usuario a eliminar no es el que tiene sesion activa
                if ($_POST['txtId'] != $_SESSION['idusuario']) {
                    if ($usuario->setId($_POST['txtId'])) {
                        if($usuario->setAccion($_POST['txtAccion'])){
                            if ($usuario->readOne()) {
                                // Ejecutamos la funcion para eliminar datos
                                if ($usuario->deleteRow()) {
                                    $result['status'] = 1;
                                    $result['message'] = 'Estado actualizado correctamente';
                                } else {
                                    $result['exception'] = Database::getException();
                                }
                            } else {
                                $result['exception'] = 'Usuario inexistente';
                            }
                        } else {
                            $result['exception'] = 'Estado incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Usuario incorrecto';
                    }
                } else {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
                // Caso para cargar todos datos de la tabla
            case 'readAll':
                // Ejecutamos la funcion del modelo
                if ($usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un usuario registrado';
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No existen usuarios registrados';
                    }
                }
                break;
            // Caso para registrar un usuario en la base de datos
            case 'register':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $usuario->validateForm($_POST);
                $usuario->setId(1);
                $usuario->setTipo(1);
                if (!$usuario->readAll()) {
                    if ($usuario->setCorreo($_POST['txtCorreo'])) {
                        if ($usuario->setAlias($_POST['txtAlias'])) {
                            // Comparamos los valores de las claves ingresadas
                            if ($_POST['txtClave1'] == $_POST['txtClave2']) {
                                if ($usuario->setClave($_POST['txtClave1'])) {
                                    if ($usuario->setDui($_POST['txtDui'])) {
                                        if ($usuario->setTelefono($_POST['txtTelefono'])) {
                                            // Ejecutamos la funcion para ingresar al usuario
                                            if ($usuario->createRow()) {
                                                $result['status'] = 1;
                                                $result['message'] = 'Usuario registrado correctamente';
                                            } else {
                                                // Se ejecuta si existe algun error en la base de datos 
                                                $result['exception'] = Database::getException();
                                            }
                                        } else {
                                            $result['exception'] = 'Telefono incorrecto';
                                        }  
                                    } else {
                                        $result['exception'] = 'Dui incorrecto';
                                    }
                                } else {
                                    $result['exception'] = $usuario->getPasswordError();
                                }
                            } else {
                                $result['exception'] = 'Claves diferentes';
                            }
                        } else {
                            $result['exception'] = 'Alias incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Correo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Existen usuarios registrados';
                }
                break;
            // Caso para iniciar sesion en el sistema 
            case 'logIn':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $usuario->validateForm($_POST);
                // Ejecutamos la funcion para verificar si existe el usuario
                if ($usuario->checkUser($_POST['txtAlias'])) {
                    if ($usuario->checkState($_POST['txtAlias'])) {
                        // Ejecutamos la funcion para verificar si la clave es correcta
                        if ($usuario->checkPassword($_POST['txtClave'])) {
                            $result['status'] = 1;
                            // Asignamos los valores a las variables de sesion
                            $_SESSION['idusuario'] = $usuario->getId();
                            $_SESSION['tipo'] = $usuario->getTipo();
                            $_SESSION['correo'] = $usuario->getCorreo();
                            $_SESSION['usuario'] = $usuario->getAlias();
                            $_SESSION['telefono'] = $usuario->getTelefono();
                            // Mostramos mensaje de exito
                            $result['message'] = 'Acceso concedido';
                        } 
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            // Mensaje de usuario inactivo
                            $result['exception'] = 'El usuario se encuentra inactivo';
                        }
                    }
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Usuario incorrecto';
                    }
                }
                break;
            // Caso para cerrar sesion por inactividad del usuario
            case 'logOut2':
                // Ejecutamos el metodo para cerrar sesion
                unset($_SESSION['idusuario']);
                $result['status'] = 1;
                // Mostramos mensaje de confirmacion
                $result['message'] = 'La sesión ha expirado';
                break;
                // Si el caso no coincide con ninguno de los casos anteriores se muestra el siguiente mensaje
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
