<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar facturas', 'Facturas');
?>

<section class="inner-page">
  <div class="container">
    <div class="row">
      <!-- Formulario de búsqueda -->
      <div class="row">
        <div class="col col-lg-10 col-md-8">
          <div class="row">
            <div class="col col-md-8 col-lg-6">
              <!-- Formulario de búsqueda -->
              <form method="post" id="search-form">
                <div class="input-field">
                  <input type="text" name="search" class="form-control" id="search" placeholder="Buscar por nombre de cliente">
                </div>
              </form>
            </div>

            <div class="col col-md-4 col-lg-6">
              <div class="input-field">
                <button type="button" onclick="search()" class="btn btn-dark" data-tooltip="Buscar"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <br><br>

    <div class="container">
      <table class="table">
        <thead class="table-dark">
          <tr id="tableHeader">
            <th>ID</th>
            <th>Cliente</th>
            <th>Estado de factura</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbody-rows">
        </tbody>
      </table>
    </div> <br>

    <!-- Modal para registrar/actualizar facturas -->
    <div class="modal fade" id="modalDatos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDatosLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="modal-title" name="modal-title" class="modal-title">REGISTRAR/ACTUALIZAR FACTURA</h5>
          </div>
          <div class="modal-body">
            <form method="post" id="save-form" enctype="multipart/form-data">
              <input class="hide" type="number" id="auxId" name="auxId" />

              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label>Cliente*</label>
                    <input id="txtCliente" name="txtCliente" type="text" class="form-control" placeholder="Nombre del cliente" required>
                  </div><br>
                  <div class="form-group">
                    <label>Estado*</label>
                    <select id="cmbEstado" name="cmbEstado" class="form-control" required>
                      <!-- Llenado dinámico de opciones de estado de la factura -->
                    </select>
                  </div><br>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label>Vendedor*</label>
                    <select id="cmbVendedor" name="cmbVendedor" class="form-control" required>
                      <!-- Llenado dinámico de opciones de vendedor -->
                    </select>
                  </div><br>
                  <div class="form-group">
                    <label>Fecha*</label>
                    <input type="date" id="txtFecha" name="txtFecha" class="form-control" required>
                  </div><br>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar ventana</button>
                <button onclick="saveData()" type="button" class="btn btn-dark">Guardar cambios</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para mostrar productos de una factura -->
    <div class="modal fade" id="address-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="modal-title" class="modal-title">Detalles de la factura</h5>
          </div>
          <div class="modal-body">
            <form method="post" id="address-form" enctype="multipart/form-data">
              <input class="d-none" type="number" id="txtIdx" name="txtIdx" />
            </form>

            <table class="table">
              <thead class="table-dark">
                <tr id="tableHeader">
                  <th>Producto</th>
                  <th>Precio unitario</th>
                  <th>Cantidad</th>
                  <th>Total unitario</th>
                </tr>
              </thead>
              <tbody id="tbody-rows2">
              </tbody>
            </table><br>

            <div class="row right-align">
              <p>TOTAL A PAGAR (US$) <b id="pago"></b></p>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Dashboard_Page::footerTemplate('facturas.js');
?>
