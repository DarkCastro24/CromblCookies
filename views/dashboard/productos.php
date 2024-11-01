<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Administrar productos','Productos');
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
                            <input type="text" name="search" class="form-control" id="search" placeholder="Buscar por nombre de producto y categoría">
                        </div>
                    </form>
                  </div>

                  <div class="col col-md-4 col-lg-6">
                    <div class="input-field">
                      <button type="button" onclick="searchProduct()" class="btn btn-dark" data-tooltip="Buscar"><i class="fa fa-search"></i></button>
                      <!--<a href="../../app/reports/dashboard/productos.php" target="_blank" class="btn btn-dark" data-tooltip="Reporte de usuarios por tipo"><i class="fa fa-file-text" aria-hidden="true"></i></a> -->
                    </div>
                  </div>

                </div>

              </div>
                
              <div class="col col-lg-2 col-md-4">
             
                <div class="input-field">
                    <!-- Enlace para abrir la caja de dialogo (modal) al momento de crear un nuevo registro -->
                    <button type="button" onclick="openCreateModal()" class="btn btn-dark">Agregar producto</button>
                </div>
                
              </div>
        </div>
      </div> <br><br>

      <div class="container">
        <table class="table">
          <thead class="table-dark">
            <tr id="tableHeader">
                <th>Imagen</th>
                <th>Categoría</th>
                <th>Marca</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Visible</th>
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
              <h5 id="modal-title" name="modal-title" class="modal-title" id="modalDatosLabel">REGISTRAR/ACTUALIZAR PRODUCTOS</h5>
            </div>
            <div class="modal-body">
              <form method="post" id="save-form" enctype="multipart/form-data">
                <input type="hidden" id="auxId" name="auxId">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Producto*</label>
                            <input autocomplete="off" id="txtProducto" name="txtProducto" type="text" class="form-control" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                        </div><br>
                        <div class="form-group">
                            <label>Categoria</label>
                            <select id="cmbCategoria" name="cmbCategoria" class="form-control">
                            </select>
                        </div><br>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Precio (US$)</label>
                            <input autocomplete="off" id="txtPrecio" name="txtPrecio" type="number" maxlength="60" class="form-control" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required>
                        </div><br>
                    <div class="form-group">
                        <label>Marca</label>
                        <select id="cmbMarca" name="cmbMarca" class="form-control">
                        </select>
                    </div><br>
                </div>
                <div class="col-12">
                    <div class="form-group">
                      <label for="txtDescripcion" class="form-label">Descripcion producto</label>
                      <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" type="text" class="form-control" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio" required rows="3"></textarea>
                    </div><br>
                </div>
                <div class="file-field input-field col-12">
                    <label for="txtDescripcion" class="form-label">Imagen producto</label>
                    <div class="input-group" data-tooltip="Seleccione una imagen de al menos 500x500">
                    <input type="file" class="form-control" id="archivo_producto" name="archivo_producto" accept=".gif, .jpg, .png" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
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
  </main>
  <?php
    // Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
    Dashboard_Page::footerTemplate('productos.js');
  ?>
