// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_PRODUCTOS = '../../app/api/dashboard/productos.php?action=';
const ENDPOINT_CATEGORIAS = '../../app/api/dashboard/productos.php?action=readCategoria';
const ENDPOINT_MARCA = '../../app/api/dashboard/productos.php?action=readMarca';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_PRODUCTOS);
});

function openUpdateModal(idProducto) {
    // Restablecer los elementos del formulario.
    document.getElementById('save-form').reset();
    document.getElementById('archivo_producto').required = false;

    // Cambiar el título del modal para indicar que es una actualización.
    document.getElementById('modal-title').textContent = 'ACTUALIZAR PRODUCTO';

    // Realizar una solicitud para obtener los datos del producto seleccionado.
    const data = new FormData();
    data.append('txtId', idProducto); // Aquí se envía el ID del producto al servidor para obtener los detalles.

    fetch(API_PRODUCTOS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    // Asignar los valores de la respuesta a los campos del formulario.
                    document.getElementById('auxId').value = response.dataset.id_producto; // Asignar el ID al campo oculto.
                    document.getElementById('txtProducto').value = response.dataset.nombre_producto;
                    document.getElementById('txtPrecio').value = response.dataset.precio_producto;
                    document.getElementById('txtDescripcion').value = response.dataset.descripcion_producto;
                    fillSelect(ENDPOINT_CATEGORIAS, 'cmbCategoria', response.dataset.id_categoria);
                    fillSelect(ENDPOINT_MARCA, 'cmbMarca', response.dataset.id_marca);
                    
                    // Abrir el modal.
                    $('#modalDatos').modal('show');
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}


// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    let icon = '';
    let accion = '';

    dataset.map(function (row) {
        if (row.estado_producto == true) {
            icon = 'fa fa-lock';
            accion = false;
        } else {
            icon = 'fa fa-unlock-alt';
            accion = true;
        }

        content += `
            <tr>
                <td><img src="../../resources/img/productos/${row.imagen_producto}" class="materialboxed" height="100"></td>
                <td>${row.categoria_producto}</td>
                <td>${row.marca_producto}</td>
                <td>${row.nombre_producto}</td>
                <td>$${row.precio_producto}</td>
                <td>${row.estado_producto}</td>
                <td>
                    <a href="#" onclick="openDeleteDialog(${row.id_producto}, ${accion})" class="btn waves-effect waves-orange btn deleteButton tooltipped" data-tooltip="Eliminar"><i class="${icon} fa-lg"></i></a>
                    <a href="#" onclick="openUpdateModal(${row.id_producto})" class="btn waves-effect btn updateButton tooltipped" data-tooltip="Actualizar"><i class="fa fa-refresh fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        `;
    });

    document.getElementById('tbody-rows').innerHTML = content;

    // Después de llenar la tabla, asigna los eventos a los botones de actualizar.
    document.querySelectorAll('.updateButton').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const idProducto = button.getAttribute('onclick').match(/\d+/)[0]; // Obtiene el ID del producto del onclick.
            openUpdateModal(idProducto);
        });
    });
}


// Funcion para busqueda filtrada 
function searchProduct() {
    searchRows(API_PRODUCTOS, 'search-form');
}

// Función para preparar el formulario al momento de insertar un registro.
function openCreateModal() {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    document.getElementById('archivo_producto').required = true;
    fillSelect(ENDPOINT_CATEGORIAS, 'cmbCategoria', null);
    fillSelect(ENDPOINT_MARCA, 'cmbMarca', null);
    // Ocultamos el input que contiene el ID del registro
    document.getElementById('auxId').style.display = 'none';
    // Abrimos el modal con JQuery
    $('#modalDatos').modal('show');
}

// Funcion para guardar o modificar datos (se llama en el boton guardar del modal)
async function saveData() {
    let action = '';
    if (document.getElementById('auxId').value) {
        action = 'update';
    } else {
        action = 'create';
    }

    const formData = new FormData(document.getElementById('save-form'));

    // Verificar si se ha seleccionado un archivo de imagen.
    const archivoInput = document.getElementById('archivo_producto');
    if (archivoInput.files.length > 0) {
        formData.append('archivo_producto', archivoInput.files[0]);
    }

    try {
        const response = await fetch(API_PRODUCTOS + action, {
            method: 'post',
            body: formData
        });

        if (response.ok) {
            const text = await response.text();
            try {
                const data = JSON.parse(text); // Intentar convertir la respuesta en JSON
                if (data.status) {
                    sweetAlert(1, data.message, null);
                    // Recargar los datos de la tabla.
                    readRows(API_PRODUCTOS);
                    // Cerrar el modal.
                    $('#modalDatos').modal('hide');
                } else {
                    sweetAlert(2, data.exception, null);
                }
            } catch (e) {
                console.error("La respuesta no es un JSON válido:", text);
                sweetAlert(2, 'Ocurrió un error inesperado. Contacte al administrador.', null);
            }
        } else {
            console.log(`${response.status} ${response.statusText}`);
            sweetAlert(2, 'Ocurrió un error al procesar la solicitud. Inténtalo nuevamente.', null);
        }
    } catch (error) {
        console.log(error);
        sweetAlert(2, 'Ocurrió un error inesperado. Inténtalo nuevamente.', null);
    }
}


// Función para establecer el registro a eliminar y abrir una caja de dialogo de confirmación.
function openDeleteDialog(id,accion) {
    const data = new FormData();
    data.append('txtId', id);
    data.append('txtAccion', accion);
    // Ejecutamos la funcion para eliminar un producto
    confirmDelete(API_PRODUCTOS, data);
}