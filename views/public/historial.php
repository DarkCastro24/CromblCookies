<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('GameBridge | Historial de compras', null);
?>

<style>
    .centrar {
        align-items: center;
        text-align: center;
        padding-top: 45px;
        padding-bottom: 35px;
        font-size: 35px;
    }

    .anchoModal{
        width: 900px;
    }

    .espacioModal{
        padding-top: 30px;
        padding-bottom: 40px;
    }
</style>

<div class="container">

    <h1 class="centrar">Historial de compras</h1>
    <table class="table">
        <thead class="table-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Fecha pedido</th>
                <th scope="col">Estado</th>
                <th scope="col">Total</th>
                <th scope="col">Detalle pedido</th>
            </tr>
        </thead>
        <tbody id="tb-rows">

        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content anchoModal">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Detalle de compra</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body espacioModal">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Imagen</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Precio unitario</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="tb-rows2">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar ventana</button>
            </div>
        </div>
    </div>
</div>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('historial.js');
?>