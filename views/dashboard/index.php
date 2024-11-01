<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerLogin('Inicio de sesión');
?>

<div class="min-h-screen flex flex-col items-center justify-center p-4" style="background-color: #fbbccb; width: 100%; height: 100%; margin: 0;">
  <div class="text-center mb-6" style="padding: 1rem;">
    <div class="flex items-center justify-center">
      <img src="../../resources/img/cookie.png" alt="Logo" class="title-animation" style="width: 50px; height: 50px; margin-right: 10px;">
      <h1 class="text-4xl font-bold title-animation" style="color: #000000;">Crombl Cookies</h1>
    </div>
  </div>
  <div class="card w-full max-w-5xl shadow-lg rounded-lg overflow-hidden mx-auto flex items-center justify-center py-24 h-full min-h-[800px]">
    <div class="row g-0">
      <div class="col-md-6 p-12" style="background-color: #ffffff; height: 100%;">
        <div class="card-header text-center">
          <h2 class="text-2xl font-bold" style="color: #d5408e;">Inicio de sesión</h2>
        </div>
        <div class="card-body">
          <form method="post" id="session-form" class="space-y-4">
            <div class="form-outline mb-4">
              <label class="form-label font-bold" style="color: #c93384;" for="txtAlias"><strong>Usuario</strong></label>
              <input type="text" id="txtAlias" name="txtAlias" class="form-control border border-gray-300 rounded-lg p-2" placeholder="Ingrese su nombre de usuario" style="background-color: #ffe0ee; box-shadow: 0 0 5px 2px #f77bb0;" required />
            </div>

            <div class="form-outline mb-4">
              <label class="form-label font-bold" style="color: #c93384;" for="txtClave"><strong>Contraseña</strong></label>
              <input type="password" id="txtClave" name="txtClave" class="form-control border border-gray-300 rounded-lg p-2" placeholder="Ingrese su contraseña" style="background-color: #ffe0ee; box-shadow: 0 0 5px 2px #f77bb0;" required />
            </div>

            <!-- Google reCAPTCHA -->
            <div class="g-recaptcha mb-4" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

            <!-- Botón corregido -->
            <button type="button" id="loginButton" class="btn w-full text-white login-button rounded-lg py-2" style="background-color: #c93384; width: 100%;">Iniciar sesión</button>
          
          </form>
          <div class="mt-8 text-center">
            <a href="#" class="text-sm" style="color: #d5408e;">Olvidaste tu contraseña?</a>
          </div>
        </div>
      </div>
      <div class="col-md-6 d-flex align-items-center" class="text-white p-6">
        <div class="text-white px-3 py-4 p-md-5 mx-md-4 d-flex flex-column align-items-center justify-content-center">
          <h4 class="text-2xl font-bold mb-4" style="color: #000000;">Somos más que una simple empresa</h4>
          <p class="text-lg" style="color: #000000;">En CromblCookies nos dedicamos a hornear y ofrecer las mejores galletas artesanales que puedas imaginar. Nuestros sabores únicos y frescos están hechos con ingredientes de alta calidad para asegurarnos de que cada bocado sea inolvidable. Únete a nuestra comunidad y disfruta de la experiencia más dulce.</p>
        </div>
        <div class="text-center mt-4">
          <img src="../../resources/img/cookies-login.png" alt="Cookies Login" class="img-fluid main-cookie" style="max-width: 80%; height: auto;">
        </div>
      </div>
    </div>
  </div>
  <div class="d-flex justify-content-between align-items-center p-4" style="background-color: #fbbccb; width: 100%; margin: 0; padding: 0; box-sizing: border-box;">
    <img src="../../resources/img/cookies-rotas-login.png" alt="Cookies Rotas Login" class="img-fluid cookie-hover" style="max-width: 50%; height: auto;">
    <img src="../../resources/img/cookies-rotas-login2.png" alt="Cookies Rotas Login 2" class="img-fluid cookie-hover" style="max-width: 55%; height: auto;">
  </div>
</div>

<footer class="text-center py-8" style="background-color: #ffffff; width: 100%; margin: 0;">
  <h1 class="font-bold" style="color: #000000;">crombl</h1>
  <p style="color: #000000;">© 2024 all rights reserved.</p>
  <div class="text-sm">
    <a href="#" style="color: #000000;">Privacy policy</a> |
    <a href="#" style="color: #000000;">Terms and Conditions</a> |
    <a href="#" style="color: #000000;">Non-edible Cookie Preferences</a>
  </div>
</footer>

<style>
  .cookie-hover {
    transition: transform 0.3s ease;
  }
  .cookie-hover:hover {
    transform: scale(1.1);
  }

  .main-cookie {
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  .main-cookie:hover {
    transform: rotate(5deg);
    opacity: 0.9;
  }

  .login-button {
    transition: background-color 0.3s ease, transform 0.3s ease;
  }
  .login-button:hover {
    background-color: #d5408e;
    transform: scale(1.05);
  }

  .title-animation { transition: transform 0.3s ease, color 0.3s ease; }
  .title-animation:hover {
    transform: scale(1.1);
    color: #c93384;
  }
</style>

<?php
Dashboard_Page::footerLogin('index.js');
?>


