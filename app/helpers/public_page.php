<?php
/*
*   Clase para definir las plantillas de las páginas web del sitio público.
*/
class Public_Page
{
    public static function headerTemplate($title,$page)
    {
        session_start();
        $filename = basename($_SERVER['PHP_SELF']);
        print('
            <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="utf-8">
                    <meta content="width=device-width, initial-scale=1.0" name="viewport">   
                    <title>'.$title.'</title>
                    <link type="image/png" rel="icon" href="../../resources/img/icono.png"/> 
                    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
                    <link href="../../resources/css/bootstrap.min.css" rel="stylesheet">
                    <link href="../../resources/css/bootstrap-icons.css" rel="stylesheet">
                    <link href="../../resources/css/styles.css" rel="stylesheet">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                    <!-- Script de reCAPTCHA -->
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        ');
        if ($filename == 'productos.php') {
            print('
                <link href="../../resources/css/cards.css" rel="stylesheet">
            ');
        }
        // Se obtiene el nombre del archivo de la página web actual.
        $filename = basename($_SERVER['PHP_SELF']);
        // Se comprueba si existe una sesión de cliente para mostrar el menú de opciones, de lo contrario se muestra otro menú.
        if (isset($_SESSION['id_cliente'])) {
            // Se verifica si la página web actual es diferente a login.php y register.php, de lo contrario se direcciona a index.php
            if ($filename != 'register.php' && $filename != 'signin.php') {
                print('
                </head>
                    <body>
                    <header id="header" class="d-flex align-items-center">
                        <div class="container d-flex justify-content-between">
                            <div class="logo">
                            <h1 class="text-light"><a href="index.php">GameBridge</a></h1>
                            </div>
                            <nav id="navbar" class="navbar">
                            <ul>
                                <li><a class="nav-link scrollto" href="index.php">Inicio</a></li>
                                <li><a class="nav-link scrollto" href="catalogos.php">Catalogo</a></li>
                                <li><a class="nav-link scrollto" href="cart.php">Carrito</a></li>
                                <li><a class="nav-link scrollto" href="historial.php">Historial</a></li>
                                <li><a class="nav-link scrollto" onclick="logOut()"> Usuario: '.$_SESSION['correo_cliente'].'</a></li>
                            </ul>
                            </nav>
                        </div>
                    </header>
                ');
            } else {
                header('location: index.php');
            }
        } else {
            if ($filename != 'cart.php') {
                print('
                <body>
                    <header id="header" class="d-flex align-items-center">
                        <div class="container d-flex justify-content-between">
                            <div class="logo">
                            <h1 class="text-light"><a href="index.php">GameBridge</a></h1>
                            </div>
                            <nav id="navbar" class="navbar">
                            <ul>
                                <li><a class="nav-link scrollto " href="index.php">Inicio</a></li>
                                <li><a class="nav-link scrollto " href="catalogos.php">Catalogo</a></li>
                                <li><a class="nav-link scrollto" href="register.php">Crear Cuenta</a></li>
                            <li><a class="nav-link scrollto" href="signin.php">Iniciar Sesión</a></li>     
                            </ul>
                            </nav>
                        </div>
                    </header>
                ');
            } else {
                if($filename == 'cart.php'){
                    header('location: index.php');
                }
            }
        } if ($filename != 'index.php' && $filename != 'cart.php' && $filename != 'signin.php' && $filename != 'register.php' && $filename != 'historial.php') {
            print('
            <section id="breadcrumbs" class="breadcrumbs">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 id="subtitulo">'.$page.'</h2>
                        <ol>
                        <li><a href="index.php">Inicio</a></li>
                        <li>'.$page.'</li>
                        </ol>
                    </div>
                </div>
          </section>');
        }
    }

    public static function footerTemplate($controller)
    {
        $filename = basename($_SERVER['PHP_SELF']);
        if ($filename != 'cart.php' && $filename != 'signin.php' && $filename != 'register.php') {
            print('
            <footer id="footer">
                <div class="container footer-bottom clearfix">
                    <div class="copyright">
                        &copy; Copyright <strong><span>Gamebridge</span></strong>. Derechos reservados
                    </div>
                </div>
            </footer>
            <section id="topbar" class="d-flex align-items-center">
                <div class="container d-flex justify-content-center justify-content-md-between">
                    <div class="contact-info d-flex align-items-center">
                    <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:Gamebridge@gmail.com">Gamebridge@gmail.com</a></i>
                    <i class="bi bi-phone d-flex align-items-center ms-4"><span>+503 7988 5288</span></i>
                    </div>
                    <div class="social-links d-none d-md-flex align-items-center">
                    <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></i></a>
                    </div>
                </div>
            </section>
            '  
        );
        } 
        print('
                    <script type="text/javascript" src="../../resources/js/bootstrap.bundle.min.js"></script>
                    <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
                    <script type="text/javascript" src="../../app/helpers/components.js"></script>
                    <script type="text/javascript" src="../../app/controllers/public/account.js"></script> 
                    <script type="text/javascript" src="../../app/controllers/public/' . $controller . '"></script>
                    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
                </body>
            </html>
        ');
    }
    
}
