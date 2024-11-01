<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Pagina principal','Inicio');
?>

  
<?php
    // Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
    Dashboard_Page::footerTemplate('main.js');
?>
