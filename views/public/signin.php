<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('CromblCookies | Inicio sesión','Iniciar sesión');
?>

<link type="text/css" rel="stylesheet" href="../../resources/css/login_styles.css" />

  <section class="h-100 gradient-form">
    <div class="container-fluid py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10"><br><br>
          <div class="card rounded-3 text-black h-100">
            <div class="row g-0 h-100">
              <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-4 h-100 d-flex flex-column justify-content-center">

                  <div class="text-center">
                    <h3 class="mt-1 mb-5 pb-1">Inicio de sesión</h3>
                  </div>

                  <form method="post" id="session-form">
                      <div class="form-outline mb-4">
                          <label class="form-label" for="usuario">Usuario</label>
                          <input type="email" id="usuario" name="usuario" class="form-control" placeholder="Ingrese su correo electrónico" />
                      </div>

                      <div class="form-outline mb-4">
                          <label class="form-label" for="clave">Contraseña</label>
                          <input type="password" id="clave" name="clave" class="form-control" placeholder="Ingrese su contraseña" />
                      </div>

                      <!-- Google reCAPTCHA -->
                      <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

                  </form><br>

                  <div class="text-center">
                  <center>
    <button id="btn-login" class="btn btn-dark btn-lg btn-block botonLogin" type="button">Iniciar sesión</button>
</center><br>

                  </div>
                  <div class="align-items-center justify-content-center">
                    <center><a class="text-muted" href="register.php">No tienes una cuenta?</a></center>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                  <h4 class="mb-4">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</h4>
                  <p class="small mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('signin.js');
?>