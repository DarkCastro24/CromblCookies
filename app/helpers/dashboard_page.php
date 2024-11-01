<?php
/*
*	Clase para definir las plantillas de las páginas web del sitio privado.
*/
class Dashboard_Page
{
    public static function headerLogin($title)
    {
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en las páginas web.
        session_start();
        // Se imprime el código HTML de la cabecera del documento.
        print('       
        <!DOCTYPE html>
            <html lang="es">
              <head>
                <meta charset="utf-8"> 
                <!--Se importan los archivos CSS locales-->
                <link type="text/css" rel="stylesheet" href="../../resources/css/bootstrap.min.css" />
                <link type="text/css" rel="stylesheet" href="../../resources/css/login_styles.css" />
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.7-beta.0/inputmask.min.js"></script>
                <link rel="icon" type="image/png" href="../../resources/img/icono.png" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <!--Título del documento-->
                <title>Dashboard | ' . $title . '</title>
              </head>
            <body>
        ');

        if (isset($_SESSION['idusuario'])) {
            header('location: main.php');
        }
    }

    public static function footerLogin($controller)
    {
        print('
        </main>
        <footer>
                <script type="text/javascript" src="../../resources/js/bootstrap.bundle.min.js"></script>
                <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
                <script type="text/javascript" src="../../app/helpers/components.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/' . $controller . '"></script>
                    
                    </footer>
                </body>
            </html>
        ');
    }

    public static function headerTemplate($title, $page)
    {
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en las páginas web.
        session_start();
        // Se imprime el código HTML de la cabecera del documento.
        print('       
        <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="utf-8">
                <meta content="width=device-width, initial-scale=1.0" name="viewport">
                <title>' . $title . '</title>
                <meta content="" name="description">
                <meta content="" name="keywords">
                <link type="image/png" rel="icon" href="../../resources/img/icono.png"/> 
                <link type="text/css" rel="stylesheet" href="../../resources/css/dashboard_styles.css" />
                <link type="text/css" href="../../resources/css/bootstrap.min.css" rel="stylesheet">
                <link type="text/css" href="../../resources/css/styles.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> 
            </head>
            <body>
        ');
        // Se obtiene el nombre del archivo de la página web actual.
        $filename = basename($_SERVER['PHP_SELF']);
        // Se comprueba si existe una sesión de administrador para mostrar el menú de opciones, de lo contrario se muestra un menú vacío.
        if (isset($_SESSION['idusuario'])) {
            // Se verifica si la página web actual es diferente a index.php (Iniciar sesión) y a register.php (Crear primer usuario) para no iniciar sesión otra vez, de lo contrario se direcciona a main.php
            if ($filename != 'index.php' && $filename != 'register.php') {
                print('
                        <header id="header" class="d-flex align-items-center">
                            <div class="container d-flex justify-content-between">
                                <div class="logo">
                                    <h1 class="text-light"><a href="main.php">GameBridge</a></h1>
                                </div>
                                <nav id="navbar" class="navbar">
                                    <ul>
                                    <li><a class="nav-link scrollto " href="usuarios.php">Usuarios</a></li>
                                    <li><a class="nav-link scrollto " href="clientes.php">Clientes</a></li>
                                    <li><a class="nav-link scrollto" href="categorias.php">Categorias</a></li>
                                    <li><a class="nav-link scrollto" href="productos.php">Productos</a></li>
                                    <li><a class="nav-link scrollto" href="facturas.php">Facturas</a></li>
                                    <li><a onclick="logOut()" class="nav-link scrollto">Usuario: <b>' . $_SESSION['usuario'] . '</b><i class="fa fa-user" aria-hidden="true"></i></a></li>
                                    </ul>
                                    <i class="bi bi-list mobile-nav-toggle"></i>
                                </nav>
                            </div>
                     </header>    
                    <main id="main">
                        <section class="breadcrumbs">
                            <div class="container">');

                if ($filename != 'main.php') {
                    print('<div class="d-flex justify-content-between align-items-center">
                        <h2>Mantenimiento de ' . $page . '</h2>
                        <ol>
                            <li><a href="main.php">Inicio</a></li>
                            <li>' . $page . '</li>
                        </ol>
                        </div>
                    </div>
                    </section>');
                } else {
                    print('<div class="d-flex justify-content-between align-items-center">
                        <h2>Bienvenido al sistema ' . $_SESSION['usuario'] . '</h2>
                        <ol>
                            <li><a href="main.php">Pagina principal</a></li>
                            <li>Tipo usuario: ' . $_SESSION['tipo'] . '</li>
                        </ol>
                        </div>
                    </div>
                    </section>');
                }
            } else {
                header('location: main.php');
            }
        } else {
            header('location: index.php');
        }
    }

    public static function footerTemplate($controller)
    {
        $filename = basename($_SERVER['PHP_SELF']);
        if ($filename != 'productos.php') {
            print('
            </main>
            <footer id="footer">
                <div class="container footer-bottom clearfix">
                <div class="copyright">
                    &copy; Copyright <strong><span>Gamebridge</span></strong>. Derechos reservados
                </div>
                <div class="credits">
                </div>
                </div>
            </footer>
                <script type="text/javascript" src="../../resources/js/bootstrap.bundle.min.js"></script>
                <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
                <script type="text/javascript" src="../../app/helpers/components.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/logout.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/' . $controller . '"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            </body>
            </html>');
        } else {
            print(' 
                <script type="text/javascript" src="../../resources/js/bootstrap.bundle.min.js"></script>
                <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
                <script type="text/javascript" src="../../app/helpers/components.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/logout.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/' . $controller . '"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            </body>
            </html>');
        }
    }
}
