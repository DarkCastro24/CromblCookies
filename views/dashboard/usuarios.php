<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar usuarios', 'Usuarios');
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
                  <input type="text" name="search" class="form-control" id="search" placeholder="Buscar por usuario">
                </div>
              </form>
            </div>

            <div class="col col-md-4 col-lg-6">
              <div class="input-field">

                <button type="button" onclick="searchUser()" class="btn btn-dark" data-tooltip="Buscar"><i class="fa fa-search"></i></button>
                <!-- <a href="../../app/reports/dashboard/usuarios.php" target="_blank" class="btn btn-dark" data-tooltip="Reporte de usuarios por tipo"><i class="fa fa-file-text" aria-hidden="true"></i></a> -->
              </div>
            </div>

          </div>

        </div>

        <div class="col col-lg-2 col-md-4">

          <div class="input-field">
            <!-- Enlace para abrir la caja de dialogo (modal) al momento de crear un nuevo registro -->
            <button type="button" onclick="openCreateModal()" class="btn btn-dark">Agregar usuario</button>
          </div>

        </div>
      </div>
    </div> <br><br>

    <div class="container">
      <table class="table">
        <thead class="table-dark">
          <tr id="tableHeader">
            <th>ID</th>
            <th>Usuario</th>
            <th>DUI</th>
            <th>Telefono</th>
            <th>Tipo</th>
            <th>Correo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbody-rows">
        </tbody>
      </table>
    </div> <br>

    <!-- Modal -->
    <div class="modal fade" id="modalDatos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDatosLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 id="modal-title" name="modal-title" class="modal-title" id="modalDatosLabel">REGISTRAR/ACTUALIZAR USUARIO</h5>
          </div>
          <div class="modal-body">
            <form method="post" id="save-form" enctype="multipart/form-data">
              <input type="number" id="auxId" name="auxId" />
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label>Código*</label>
                    <input id="txtId" name="txtId" type="number" min="1" max="999999" class="form-control" placeholder="1" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                    <div id="emailHelp" class="form-text">Identificador del usuario, campo unico.</div>
                  </div><br>
                  <div class="form-group">
                      <label>Teléfono*</label>
                      <input autocomplete="off" id="txtTelefono" name="txtTelefono" type="text" maxlength="9" class="form-control" placeholder="0000-0000" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                      <div id="emailHelp" class="form-text">Debes ingresar un teléfono válido (XXXX-XXXX).</div>
                  </div><br>
                  <div class="form-group">
                    <label>Clave*</label>
                    <input autocomplete="off" id="txtClave" name="txtClave" type="password" maxlength="30" class="form-control" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                  </div><br>
                  <div class="form-group">
                    <label>Correo*</label>
                    <input autocomplete="off" id="txtCorreo" name="txtCorreo" type="email" maxlength="60" class="form-control" placeholder="correo@example.com" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                    <div id="emailHelp" class="form-text">Debes ingresar un correo valido.</div>
                  </div><br>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label>Usuario*</label>
                    <input autocomplete="off" id="txtUsuario" name="txtUsuario" type="text" maxlength="60" class="form-control" placeholder="username" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                    <div id="emailHelp" class="form-text">El usuario debe ser unico.</div>
                  </div><br>
                  <div class="form-group">
                      <label>DUI*</label>
                      <input autocomplete="off" id="txtDui" name="txtDui" type="text" maxlength="10" class="form-control" placeholder="00000000-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                      <div id="emailHelp" class="form-text">Ingresa el DUI con guion (XXXXXXXX-X).</div>
                  </div><br>
                  <div class="form-group">
                    <label>Confirmar clave*</label>
                    <input autocomplete="off" id="txtClave2" name="txtClave2" type="password" maxlength="30" class="form-control" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                  </div><br>
                  <div class="form-group">
                    <label>Tipo usuario*</label>
                    <select id="cmbTipo" name="cmbTipo" class="form-control">
                    </select>
                    <div id="emailHelp" class="form-text">Según tipo usuario diferentes privilegios.</div>
                  </div><br>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar ventana</button>
            <button onclick="saveData()" type="button" class="btn btn-dark">Guardar cambios</button>
          </div>
        </div>
      </div>
    </div>
</section>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Dashboard_Page::footerTemplate('usuarios.js');
?>