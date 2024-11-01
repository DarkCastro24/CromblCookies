<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/clientes.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cliente = new Clientes;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['id_cliente'])) {
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión eliminada correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'register': 
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $cliente->validateForm($_POST);
                if ($cliente->setNombres($_POST['nombre'])) {
                    if ($cliente->setApellidos($_POST['apellido'])) {
                        if ($cliente->setDui($_POST['dui'])) {
                            if ($cliente->setCorreo($_POST['correo'])) {
                                if ($cliente->setClave($_POST['clave'])) {
                                    if ($_POST['clave'] == $_POST['clave2']) {
                                        if ($cliente->setClave($_POST['clave'])) {
                                            // Ejecutamos la funcion del modelo 
                                            if ($cliente->createRow()) {
                                                $result['status'] = 1;
                                                $result['message'] = 'Cliente registrado correctamente';
                                            } else {
                                                $result['exception'] = Database::getException();           
                                            } 
                                        } else {
                                            $result['exception'] = $cliente->getPasswordError();
                                        }
                                    } else {
                                        $result['exception'] = 'Las claves no coinciden';
                                    }                                                               
                                } else {
                                    $result['exception'] = $cliente->getPasswordError();
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

            case 'logIn':
                $_POST = $cliente->validateForm($_POST);

                // Verificación del reCAPTCHA
                if (isset($_POST['g-recaptcha-response'])) {
                    $recaptchaResponse = $_POST['g-recaptcha-response'];
                    $secretKey = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'; // Clave secreta de desarrollo (reemplaza en producción).
                    
                    // Solicitud a la API de Google para verificar el reCAPTCHA
                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $data = array(
                        'secret' => $secretKey,
                        'response' => $recaptchaResponse
                    );

                    $options = array(
                        'http' => array(
                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n", // Especifica el tipo de contenido
                            'method'  => 'POST',
                            'content' => http_build_query($data) // Codifica los datos
                        )
                    );

                    $context  = stream_context_create($options);
                    $verify = file_get_contents($url, false, $context);
                    $captchaSuccess = json_decode($verify);

                    // Verificación del resultado del reCAPTCHA
                    if ($captchaSuccess->success) {
                        // CAPTCHA validado correctamente, continuar con la autenticación
                        $result['recaptcha'] = 1; // Marcar que el reCAPTCHA fue exitoso

                        // Lógica de autenticación: verificar usuario y contraseña
                        if ($cliente->checkUser($_POST['usuario'])) {
                            if ($cliente->getEstado()) {
                                if ($cliente->checkPassword($_POST['clave'])) {
                                    $_SESSION['id_cliente'] = $cliente->getId();
                                    $_SESSION['correo_cliente'] = $cliente->getCorreo();
                                    $result['status'] = 1;
                                    $result['message'] = 'Autenticación correcta';
                                } else {
                                    if (Database::getException()) {
                                        $result['exception'] = Database::getException();
                                    } else {
                                        $result['exception'] = 'Clave incorrecta';
                                    }
                                }
                            } else {
                                $result['exception'] = 'La cuenta ha sido desactivada';
                            }
                        } else {
                            if (Database::getException()) {
                                $result['exception'] = Database::getException();
                            } else {
                                $result['exception'] = 'Alias incorrecto';
                            }
                        }
                    } else {
                        $result['exception'] = 'Verificación del CAPTCHA fallida, por favor intente de nuevo.';
                    }
                } else {
                    $result['exception'] = 'No se recibió el CAPTCHA.';
                }
                break;

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
