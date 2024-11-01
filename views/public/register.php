<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('Gamebridge | Registro', 'Iniciar sesión');
?>

<style>  
  .espacio-register {
    padding-top: 30px;
  }

  body {
    background-color: #d9d4d4;
  }
</style>

<div class="container py-1 fondo-register">
  <div class="row g-0 align-items-center espacio-register">
    <div class="col-lg-7 mb-7 mb-lg-0">
      <div class="card cascading-right" style="background: hsla(0, 0%, 100%, 0.55); backdrop-filter: blur(30px);">
        <div class="card-body p-4 shadow-5 ">
          <h2 class="fw-bold mb-5 text-center">Registro de clientes</h2>

          <form method="post" id="register-form">

            <div class="row">
              <div class="col-md-6 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="nombre">Nombre</label>
                  <input type="text" id="nombre" name="nombre" class="form-control form-control-lg" required />
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="apellido">Apellido</label>
                  <input type="text" id="apellido" name="apellido" class="form-control form-control-lg" required />
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="correo">Correo electrónico</label>
                  <input type="email" id="correo" name="correo" class="form-control form-control-lg" required />
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="dui">DUI</label>
                  <input type="text" id="dui" name="dui" class="form-control form-control-lg" required pattern="^[0-9]{8}-[0-9]$" placeholder="XXXXXXXX-X"/>
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="clave">Clave</label>
                  <input type="password" id="clave" name="clave" class="form-control form-control-lg" required />
                </div>
              </div>
              <div class="col-md-6 mb-4">
                <div class="form-outline">
                  <label class="form-label" for="clave2">Confirmar clave</label>
                  <input type="password" id="clave2" name="clave2" class="form-control form-control-lg" required />
                </div>
              </div>
            </div>    

            <!-- Google reCAPTCHA -->
            <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

          </form><br>
          <center><button id="register-button" class="btn btn-dark btn-lg btn-block botonRegister" type="button">Registrarse</button></center>
        </div>
      </div>
    </div>
    <div class="col-lg-5 mb-5 mb-lg-0">
      <img src="https://mdbootstrap.com/img/new/ecommerce/vertical/004.jpg" class="w-95 rounded-4 shadow-4" alt=""/>
    </div>
  </div>
</div>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('register.js');
?>
