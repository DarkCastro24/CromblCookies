// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CATALOGO = '../../app/api/public/catalogo.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que muestra las categorías disponibles.
    readAllCategorias();
});

// Función para obtener y mostrar las categorías disponibles.
function readAllCategorias() {
    fetch(API_CATALOGO + 'readAll', {
        method: 'get'
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    let content = '';
                    let url = '';
                    response.dataset.map(function (row) {
                        if (row.cantidad != 0) {
                            url = `productos.php?id=${row.id_categoria}&categoria=${row.categoria}`;
                            content += `
                                <div class="col col-sm-12 col-md-6 col-lg-4 col-xl-3 cardSpace">
                                    <div class="card">
                                    <img src="../../resources/img/categorias/${row.imagen}" alt="" class="card-img-top">
                                        <div class="card-body">
                                            <h5 class="card-title">${row.categoria}</h5>
                                            <p class="card-text">${row.descripcion}</p>
                                            
                                            <div class="categoryButtonAlign">
                                                <a href="${url}" class="btn btn-outline-dark categoryButton"> Ver productos</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            `;   
                            }
                    });
                    // Se agregan las tarjetas a la etiqueta div mediante su id para mostrar las categorías.
                    document.getElementById('categorias').innerHTML = content;
                } else {
                    // Se presenta un mensaje de error cuando no existen datos para mostrar.
                    document.getElementById('title').innerHTML = `<i class="material-icons small">cloud_off</i><span class="red-text">${response.exception}</span>`;
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}