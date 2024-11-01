// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENTES= '../../app/api/dashboard/clientes.php?action=';
const API_TIPOUSUARIO = '../../app/api/dashboard/usuarios.php?action=readUserType';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    readRows(API_CLIENTES);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    let icon = '';
    let accion = '';
    let estado = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        if(row.estado_cliente == true)
        {
            icon = 'fa fa-lock';
            accion = false;
            estado = 'Activo';
        } else {
            icon  = 'fa fa-unlock-alt';
            accion = true;
            estado = 'Inactivo';
        }
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr>
                <td>${row.id_cliente}</td>
                <td>${estado}</td>
                <td>${row.nombres_cliente}</td>
                <td>${row.apellidos_cliente}</td>
                <td>${row.dui_cliente}</td>
                <td>${row.correo_cliente}</td>
                <td>
                    <a href="#" onclick="openDeleteDialog(${row.id_cliente},${accion})" class="btn waves-effect waves-orange btn deleteButton tooltipped" data-tooltip="Eliminar"><i class="${icon} fa-lg"></i></a>
                    <a href="#" onclick="openUpdateModal(${row.id_cliente})" class="btn waves-effect btn updateButton tooltipped" data-tooltip="Actualizar"><i class="fa fa-refresh fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows').innerHTML = content;
}

// Funcion para busqueda filtrada 
function searchUser() {
    searchRows(API_CLIENTES, 'search-form');
}

// Función para preparar el formulario al momento de insertar un registro.
function openCreateModal() {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    document.getElementById('txtClave').disabled = false;
    document.getElementById('txtClave2').disabled = false;
    document.getElementById('txtDui').disabled = false;
    // Ocultamos el input que contiene el ID del registro
    document.getElementById('auxId').style.display = 'none';
    // Abrimos el modal con JQuery
    $('#modalDatos').modal('show');
}

// Funcion para guardar o modificar datos (se llama en el boton guardar del modal)
function saveData() {
    if (document.getElementById("txtClave").value != '') {
        if (document.getElementById("txtClave").value == document.getElementById("txtClave2").value) {
            let action = '';
            if (document.getElementById('auxId').value) {
                action = 'update';
            } else {
                action = 'create';
            }
            saveRow(API_CLIENTES, action, 'save-form', 'modalDatos');
        } else {
            sweetAlert(3, 'Las claves ingresadas no coinciden', null,'Confirme su contraseña');
        }
    } else {
        sweetAlert(3, 'Complete todos los campos solicitados', null,'No dejes campos vacios');
    }
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdateModal(id) {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    var myModal = new bootstrap.Modal(document.getElementById('modalDatos'));
    myModal.show();
    document.getElementById('modal-title').textContent = 'ACTUALIZAR USUARIO';
    document.getElementById('auxId').style.display = 'none';
    document.getElementById('txtClave').disabled = true;
    document.getElementById('txtClave2').disabled = true;
    document.getElementById('txtDui').disabled = true;
    document.getElementById('auxId').value = id;

    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('txtId', id);

    fetch(API_CLIENTES + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('auxId').value = id;
                    document.getElementById('txtNombre').value = response.dataset.nombres_cliente;
                    document.getElementById('txtApellido').value = response.dataset.apellidos_cliente;
                    document.getElementById('txtDui').value = response.dataset.dui_cliente;
                    document.getElementById('txtCorreo').value = response.dataset.correo_cliente;
                    document.getElementById('txtClave').value = response.dataset.clave;
                    document.getElementById('txtClave2').value = response.dataset.clave;
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

// Función para establecer el registro a eliminar y abrir una caja de dialogo de confirmación.
function openDeleteDialog(id, accion) {
    const data = new FormData();
    data.append('txtId', id);
    data.append('txtAccion', accion);
    confirmDelete(API_CLIENTES, data);
}

