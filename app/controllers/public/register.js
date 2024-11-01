// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENTES = '../../app/api/public/clientes.php?action=';

let loginAttempts = 0; 

document.getElementById('register-button').addEventListener('click', function() {
    if (validarFormulario()) {
        registrarCliente();
    }
});

// Función para validar el formulario de registro.
function validarFormulario() {
    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const dui = document.getElementById('dui').value.trim();
    const clave = document.getElementById('clave').value;
    const clave2 = document.getElementById('clave2').value;
    const captchaResponse = grecaptcha.getResponse();

    if (!nombre || !apellido || !correo || !dui || !clave || !clave2) {
        sweetAlert(2, 'Todos los campos deben estar llenos.', null);
        return false;
    }

    if (clave !== clave2) {
        sweetAlert(2, 'Las contraseñas no coinciden.', null);
        return false;
    }

    if (captchaResponse.length === 0) {
        sweetAlert(2, 'Por favor, complete el captcha.', null);
        return false;
    }

    return true;
}

// Función para registrar al cliente.
function registrarCliente() {
    fetch(API_CLIENTES + 'register', {
        method: 'post',
        body: new FormData(document.getElementById('register-form'))
    }).then(function(request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function(response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    sweetAlert(1, response.message, 'signin.php');
                } else {
                    sweetAlert(2, response.exception, null);
                    loginAttempts++;
                    if (loginAttempts >= 3) {
                        setTimeout(() => {
                            location.reload();
                        }, 3000); // Refresca la página después de 3 segundos.
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function(error) {
        console.log(error);
    });
}

// Aplicar una máscara al campo DUI.
document.getElementById('dui').addEventListener('input', function() {
    let valor = this.value.replace(/\D/g, ''); // Eliminar todos los caracteres no numéricos.
    if (valor.length > 8) {
        valor = valor.slice(0, 8) + '-' + valor.slice(8, 9);
    }
    this.value = valor;
});
