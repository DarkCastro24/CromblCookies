<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('Gamebridge | Categorías', 'Detalle de producto');
?>

<section id="portfolio-details" class="portfolio-details">
  <div class="container">

    <div class="row gy-4">

      <div class="col-lg-7">
        <img id="imagen" src="">
      </div>

      <div class="col-lg-5">
        <div class="portfolio-info">
          <h3 id="nombre" class="header"></h3>
          <ul>
            <b>Marca: </b>
            <p id="marca"></p>
            <b>Precio (US$): </b>
            <p id="precio"></p>
            <b>Unidades disponibles: </b>
            <p id="cantidad"></p>
            <b>Descripcion : </b>
            <p id="descripcion"></p>
          </ul>
          <hr><br>
          <!-- Formulario para agregar el producto al carrito de compras -->
          <form method="post" id="shopping-form">
            <!-- Campo oculto para asignar el id del producto -->
            <input type="hidden" id="id_producto" name="id_producto" />
            <div class="row center">
              <div class="input-field col-sm-12 col-md-3 espacioDetalle">
                <p><b>Cantidad: </b></p>
              </div>
              <div id="seccion_cantidad" class="input-field col-sm-12 col-md-3 espacioDetalle">
              </div>
              <div class="input-field col-sm-12 col-md-6">
                <button type="button" class="btn btn-dark" onclick="agregarProducto()">Agregar al carrito <i class="bi bi-cart-fill"></i> </button>
              </div>
            </div><br>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('detalle.js');
?>