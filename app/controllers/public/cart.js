// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_PEDIDOS = '../../app/api/public/pedidos.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los productos del carrito de compras para llenar la tabla en la vista.
    readOrderDetail();
});

// Función para obtener el detalle del pedido (carrito de compras).
function readOrderDetail() {
    fetch(API_PEDIDOS + 'readOrderDetail', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se declara e inicializa una variable para concatenar las filas de la tabla en la vista.
                    let content = '';
                    // Se declara e inicializa una variable para calcular el importe por cada producto.
                    let subtotal = 0;
                    // Se declara e inicializa una variable para guardar el numero de productos 
                    let numero_productos = 0;
                    // Se declara e inicializa una variable para ir sumando cada subtotal y obtener el monto final a pagar.
                    let total = 0;
                    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        subtotal = row.preciounitario * row.cantidad;
                        total += subtotal;
                        numero_productos = numero_productos + 1;
                        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                        content += `
                        <!-- Cart Item-->
                        <div class="cart-item d-md-flex justify-content-between"><span class="remove-item"><i class="fa fa-times" onclick="openDeleteDialog(${row.iddetallefactura})"></i></span>
                            <div class="px-3 my-3">
                                <a class="cart-item-product" href="#">
                                    <div class="cart-item-product-thumb"><img src="../../resources/img/productos/${row.imagen}" class="materialboxed" height="100"></div>
                                    <div class="cart-item-product-info">
                                        <h3 class="cart-item-product-title">${row.producto}</h3>
                                        <h6 style="color: black"><b>Categoria:</b> ${row.categoria}</h6>
                                        <h6 style="color: black"><b>Marca:</b> ${row.marca}</h6>
                                    </div>
                                </a>
                            </div>
                            <div class="px-3 my-3 text-center">
                                <div class="cart-item-label">Cantidad</div>
                                <div class="count-input">
                                    <select class="form-control">
                                        <option selected>${row.cantidad}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="px-3 my-3 text-center">
                                <div class="cart-item-label">Precio Unitario</div><span class="text-xl font-weight-medium">$${row.preciounitario}</span>
                            </div>
                            <div class="px-2 my-3 text-center">
                                <div class="cart-item-label">Subtotal</div><span class="text-xl font-weight-medium">$${subtotal.toFixed(2)}</span>
                            </div>
                            <div class="px-2 my-3 text-center">
                                <div class="cart-item-label">Acciones</div><span class="text-xl font-weight-medium">
                                        <button type="button" onclick="openUpdateDialog(${row.iddetallefactura}, ${row.cantidad})" class="btn btn-danger">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                </span>
                            </div>
                        </div>
                        `;
                    });
                    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
                    document.getElementById('detalle').innerHTML = content;
                    // Se muestra el total a pagar con dos decimales.
                    document.getElementById('pago').textContent = total.toFixed(2);

                    if (numero_productos == 1) {
                        document.getElementById('numeroProductos').textContent = `Tienes un producto en tu carrito`;
                    } else{
                        document.getElementById('numeroProductos').textContent = `Tienes ${numero_productos} productos en tu carrito`;
                    }
                    
                } else {
                    sweetAlert(4, response.exception, 'index.php');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

function openUpdateDialog(id, quantity) {

    // Abrir el modal utilizando JQuery
    $('#exampleModal').modal('show');

    document.getElementById('id_detalle').value = id;
    document.getElementById('cantidad').value = quantity;
}

// Función para abrir una caja de dialogo (modal) con el formulario de cambiar cantidad de producto.
function updateQuantity() {
    let id_detalle = document.getElementById('id_detalle').value;
    let cantidad = document.getElementById('cantidad').value;

    const data = new FormData();
    data.append('id_detalle', id_detalle);
    data.append('cantidad_producto', cantidad);

    fetch(API_PEDIDOS + 'updateDetail', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se actualiza la tabla en la vista para mostrar el cambio de la cantidad de producto.
                    readOrderDetail();
                    sweetAlert(1, response.message, null);
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

// Función para mostrar un mensaje de confirmación al momento de finalizar el pedido.
function finishOrder() {
    swal({
        title: 'Aviso',
        text: '¿Está seguro de finalizar el pedido?',
        icon: 'info',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para realizar la petición respectiva, de lo contrario se muestra un mensaje.
        if (value) {
            fetch(API_PEDIDOS + 'finishOrder', {
                method: 'get'
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            sweetAlert(1, response.message, 'index.php');
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
        } else {
            sweetAlert(4, 'Puede seguir comprando', null);
        }
    });
}

// Función para mostrar un mensaje de confirmación al momento de eliminar un producto del carrito.
function openDeleteDialog(id) {
    swal({
        title: 'Advertencia',
        text: '¿Está seguro de remover el producto?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para realizar la petición respectiva, de lo contrario no se hace nada.
        if (value) {
            // Se define un objeto con los datos del registro seleccionado.
            const data = new FormData();
            data.append('id_detalle', id);

            fetch(API_PEDIDOS + 'deleteDetail', {
                method: 'post',
                body: data
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Se cargan nuevamente las filas en la tabla de la vista después de borrar un producto del carrito.
                            readOrderDetail();
                            sweetAlert(1, response.message, null);
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
    });
}