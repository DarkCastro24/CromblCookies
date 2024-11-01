<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('Catalogo de productos','Productos');
?>

<div class="container-fluid bg-trasparent my-4 p-3" style="position: relative">
  <div id="productos" class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3">
   
  </div>
</div>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('productos.js');
?>