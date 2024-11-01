<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('GameBridge | Catalogos','Catalogos de productos');
?>

    <section id="services" class="services">
      <div class="container">
        <div class="row" id="categorias">

        
        </div>
      </div>
    </section>
    
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('catalogos.js');
?>