// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CATEGORIAS = '../../app/api/dashboard/categorias.php?action=';
const ENDPOINT_SECCIONES = '../../app/api/dashboard/secciones.php?action=readAll';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_CATEGORIAS);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr>
                <td>${row.id_categoria}</td>
                <td><img src="../../resources/img/categorias/${row.imagen}" class="materialboxed" height="100"></td>
                <td>${row.categoria}</td>
                <td>${row.descripcion}</td>
                <td>
                    <a href="#" onclick="openUpdateModal(${row.id_categoria})" class="btn waves-effect btn updateButton tooltipped" data-tooltip="Actualizar"><i class="fa fa-refresh fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows').innerHTML = content;
}

// Funcion para busqueda filtrada 
function searchCategories() {
    searchRows(API_CATEGORIAS, 'search-form');
}

// Función para preparar el formulario al momento de insertar un registro.
function openCreateModal() {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    document.getElementById('archivo_producto').required = true;
    // Ocultamos el input que contiene el ID del registro
    document.getElementById('auxId').style.display = 'none';
    // Abrimos el modal con JQuery
    $('#modalDatos').modal('show');
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdateModal(id) {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    // Mandamos a llamar el modal desde JS
    var myModal = new bootstrap.Modal(document.getElementById('modalDatos'));
    myModal.show();
    document.getElementById('modal-title').textContent = 'ACTUALIZAR PRODUCTO';
    document.getElementById('archivo_producto').required = false;
    // Ocultamos el input que contiene el ID del registro
    document.getElementById('auxId').style.display = 'none';
    // Creamos un form data para enviar el id 
    const data = new FormData();
    data.append('txtId', id);
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_CATEGORIAS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    // Cargamos los datos en los inputs del modal
                    document.getElementById('auxId').value = id;
                    document.getElementById('txtCategoria').value = response.dataset.categoria;
                    document.getElementById('txtDescripcion').value = response.dataset.descripcion;
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

// Funcion para guardar o modificar datos (se llama en el boton guardar del modal)
function saveData() {
    let action = '';
    if (document.getElementById('auxId').value) {
        action = 'update';
    } else {
        action = 'create';
    }
    saveRow(API_CATEGORIAS, action, 'save-form', 'modalDatos');   
}
