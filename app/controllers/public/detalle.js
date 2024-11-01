// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CATALOGO = '../../app/api/public/catalogo.php?action=';
const API_PEDIDOS = '../../app/api/public/pedidos.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se busca en la URL las variables (parámetros) disponibles.
    let params = new URLSearchParams(location.search);
    // Se obtienen los datos localizados por medio de las variables.
    const ID = params.get('id');
    // Se llama a la función que muestra el detalle del producto seleccionado previamente.
    readOneProducto(ID);
});

// Función para obtener y mostrar los datos del producto seleccionado.
function readOneProducto(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_producto', id);

    fetch(API_CATALOGO + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria.
                if (response.status) {
                    // Se colocan los datos en la tarjeta de acuerdo al producto seleccionado previamente.
                    document.getElementById('imagen').setAttribute('src', '../../resources/img/productos/' + response.dataset.imagen_producto);
                    document.getElementById('nombre').textContent = "Producto: " + response.dataset.nombre_producto;

                    // Marca, precio, descripción, cantidad
                    document.getElementById('marca').textContent = response.dataset.marca_producto;
                    document.getElementById('cantidad').textContent = response.dataset.cantidad_producto;
                    document.getElementById('precio').textContent = response.dataset.precio_producto;
                    document.getElementById('descripcion').textContent = response.dataset.descripcion_producto;

                    // Se asigna el valor del id del producto al campo oculto del formulario.
                    document.getElementById('id_producto').value = response.dataset.id_producto;

                    const subtitulo = document.getElementById("subtitulo");
                    subtitulo.innerText = "Categoría: " + response.dataset.categoria_producto;

                    const seccion = document.getElementById("seccion_cantidad");
                    seccion.innerHTML = `<input type="number" id="cantidad_producto" name="cantidad_producto" min="1" max="${response.dataset.cantidad_producto}" class="validate anchoDetail" required/>`;

                } else {
                    // Se muestra un mensaje indicando que el producto no está en la base de datos.
                    const container = document.querySelector('.portfolio-details .container');
                    container.innerHTML = `
                        <div class="alert alert-info text-center" role="alert">
                            <h4 class="alert-heading">Producto no disponible</h4>
                            <p>El producto ingresado no se encuentra en la base de datos.</p>
                        </div>
                    `;
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de agregar un producto al carrito.
/*document.getElementById('shopping-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();

    fetch(API_PEDIDOS + 'createDetail', {
        method: 'post',
        body: new FormData(document.getElementById('shopping-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
                if (response.status) {
                    sweetAlert(1, response.message, 'cart.php');
                } else {
                    // Se verifica si el cliente ha iniciado sesión para mostrar la excepción, de lo contrario se direcciona para que se autentique. 
                    if (response.session) {
                        sweetAlert(2, response.exception, null);
                    } else {
                        sweetAlert(3, response.exception, 'signin.php');
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
});*/

function agregarProducto(){
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();

    fetch(API_PEDIDOS + 'createDetail', {
        method: 'post',
        body: new FormData(document.getElementById('shopping-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
                if (response.status) {
                    sweetAlert(1, response.message, 'cart.php');
                } else {
                    // Se verifica si el cliente ha iniciado sesión para mostrar la excepción, de lo contrario se direcciona para que se autentique. 
                    if (response.session) {
                        sweetAlert(2, response.exception, null);
                    } else {
                        sweetAlert(3, response.exception, 'signin.php');
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}