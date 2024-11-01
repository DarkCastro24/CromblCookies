const API_PEDIDOS = '../../app/api/dashboard/facturas.php?action=';
const ENDPOINT_VENDEDORES = '../../app/api/dashboard/vendedores.php?action=readAll';
const ENDPOINT_ESTADO = '../../app/api/dashboard/estados_factura.php?action=readAll';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla.
    readRows(API_PEDIDOS);
});

// Función para cargar los datos dentro de la tabla del formulario
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Verificar el estado y asignar el texto correspondiente.
        let estadoText = '';
        if (row.estado === 0) {
            estadoText = 'En proceso';
        } else if (row.estado === 1) {
            estadoText = 'Finalizada';
        } else if (row.estado === 2) {
            estadoText = 'Cancelada';
        }

        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr>       
                <td>${row.id_pedido}</td>
                <td>${row.cliente}</td>
                <td>${estadoText}</td>
                <td>${row.fecha}</td>
                <td>
                    <a href="#" onclick="openAddressDialog(${row.id_pedido})" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver detalle"><i class="fa fa-file-text fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows').innerHTML = content;
    // Inicializar tooltips de Bootstrap 5
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// <a href="#" onclick="openUpdateDialog(${row.id_pedido})" class="btn btn-dark" data-bs-toggle="tooltip" title="Actualizar"><i class="fa fa-refresh fa-lg" aria-hidden="true"></i></a>

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
function search() {
    // Se llama a la función que realiza la búsqueda.
    searchRows(API_PEDIDOS, 'search-form');
}

// Función para cargar los productos de un pedido
function openAddressDialog(id) {
    getTotal(id);
    document.getElementById('save-form').reset();
    // Abrimos el modal con Bootstrap 5
    var modal = new bootstrap.Modal(document.getElementById('address-modal'));
    modal.show();
    document.getElementById('txtIdx').value = id;
    readRows2(API_PEDIDOS, 'address-form');
}

// Función para mostrar los productos por pedido en una tabla
function fillTableParam(dataset) {
    let content = '';
    dataset.map(function (row) {
        content += `
            <tr>
                <td>${row.producto}</td>
                <td>${row.preciounitario}</td>
                <td>${row.cantidad}</td>
                <td>${row.totalunitario}</td>
            </tr>
        `;
    });

    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows2').innerHTML = content;
}

// Función para limpiar la tabla
function DeleteTable() {
    let content = '';
    content += `<tr></tr>`;
    document.getElementById('tbody-rows2').innerHTML = content;
}

function openUpdateDialog(id) {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    // Abrimos el modal con Bootstrap 5
    var modal = new bootstrap.Modal(document.getElementById('save-modal'));
    modal.show();
    // Se asigna el título para la caja de dialogo (modal).
    document.getElementById('modal-title').textContent = 'Actualizar datos del pedido';

    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('txtId', id);
    // Realizamos una petición a la API indicando el caso a utilizar y enviando la dirección de la API como parámetro
    fetch(API_PEDIDOS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    fillSelect(ENDPOINT_VENDEDORES, 'cmbTipo', response.dataset.vendedor);
                    fillSelect(ENDPOINT_ESTADO, 'cmbEstado', response.dataset.estado);
                    document.getElementById('txtId').value = response.dataset.id_pedido;
                } else {
                    alert(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para mostrar el total de un pedido
function getTotal(id) {
    const data2 = new FormData();
    data2.append('txtIdx', id);

    fetch(API_PEDIDOS + 'getTotal', {
        method: 'post',
        body: data2
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    document.getElementById('pago').textContent = response.dataset.total;
                } else {
                    alert(response.exception);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('save-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario está seteado para actualizar, de lo contrario será para crear.
    if (document.getElementById('txtId').value) {
        action = 'update';
    }
    saveRow(API_PEDIDOS, action, 'save-form', 'save-modal');
});
