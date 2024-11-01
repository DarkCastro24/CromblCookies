// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';
const API_TIPOUSUARIO = '../../app/api/dashboard/usuarios.php?action=readUserType';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    readRows(API_USUARIOS);
    Inputmask("9999-9999").mask(document.getElementById("txtTelefono"));
    Inputmask("99999999-9").mask(document.getElementById("txtDui"));
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    let icon = '';
    let accion = '';
    let tipo ='';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        if(row.estado == true)
        {
            icon = 'fa fa-lock'
            accion = false;
        } else {
            icon  = 'fa fa-unlock-alt';
            accion = true;
        }
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr> 
                <td>${row.id}</td>
                <td>${row.usuario}</td>
                <td>${row.dui}</td>
                <td>${row.telefono}</td>
                <td>${row.tipo}</td>
                <td>${row.correo_electronico}</td>                             
                <td>
                    <a href="#" onclick="openDeleteDialog(${row.id},${accion})" class="btn waves-effect waves-orange btn deleteButton tooltipped" data-tooltip="Eliminar"><i class="${icon} fa-lg"></i></a>
                    <a href="#" onclick="openUpdateModal(${row.id})" class="btn waves-effect btn updateButton tooltipped" data-tooltip="Actualizar"><i class="fa fa-refresh fa-lg" aria-hidden="true"></i></a>
                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows').innerHTML = content;
}

// Funcion para busqueda filtrada 
function searchUser() {
    searchRows(API_USUARIOS, 'search-form');
}

// Función para preparar el formulario al momento de insertar un registro.
function openCreateModal() {
    document.getElementById('save-form').reset();
    document.getElementById('txtId').disabled = false;
    document.getElementById('txtClave').disabled = false;
    document.getElementById('txtClave2').disabled = false;
    document.getElementById('txtDui').disabled = false;
    document.getElementById('cmbTipo').style.display = 'block'; // Mostrar select al crear
    document.getElementById('auxId').style.display = 'none';
    fillSelect(API_TIPOUSUARIO, 'cmbTipo', null);
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
            saveRow(API_USUARIOS, action, 'save-form', 'modalDatos');
        } else {
            sweetAlert(3, 'Las claves ingresadas no coinciden', null,'Confirme su contraseña');
        }
    } else {
        sweetAlert(3, 'Complete todos los campos solicitados', null,'No dejes campos vacios');
    }
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdateModal(id) {
    document.getElementById('save-form').reset();
    var myModal = new bootstrap.Modal(document.getElementById('modalDatos'));
    myModal.show();
    document.getElementById('modal-title').textContent = 'ACTUALIZAR USUARIO';
    document.getElementById('auxId').style.display = 'none';
    document.getElementById('txtId').disabled = true;
    document.getElementById('txtClave').disabled = true;
    document.getElementById('txtClave2').disabled = true;
    document.getElementById('txtDui').disabled = true;

    // Aquí ocultamos el campo tipo de usuario al actualizar
    document.getElementById('cmbTipo').style.display = 'none';
    document.getElementById('auxId').value = id;

    const data = new FormData();
    data.append('txtId', id);
    fetch(API_USUARIOS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    document.getElementById('txtId').value = response.dataset.id;
                    document.getElementById('txtUsuario').value = response.dataset.usuario;
                    document.getElementById('txtCorreo').value = response.dataset.correo_electronico;
                    document.getElementById('txtClave').value = response.dataset.clave;
                    document.getElementById('txtTelefono').value = response.dataset.telefono;
                    document.getElementById('txtDui').value = response.dataset.dui;
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
function openDeleteDialog(id,accion) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('txtId', id);
    data.append('txtAccion', accion);
    // Se llama a la función que elimina un registro. Se encuentra en el archivo components.js
    confirmDelete(API_USUARIOS, data);
}

