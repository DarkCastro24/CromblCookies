// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';
let intentosFallidos = 0;

document.addEventListener('DOMContentLoaded', function () {
    // Petición para verificar si existen usuarios.
    fetch(API_USUARIOS + 'readAll', {
        method: 'get'
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (!response.status) {
                    sweetAlert(3, response.exception, 'register.php');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });

    // Añadir evento al botón de inicio de sesión
    document.querySelector('.botonLogin').addEventListener('click', function (event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario
        iniciarSesion(); // Llamar a la función de inicio de sesión
    });
});

async function iniciarSesion() {
    console.log("Función iniciarSesion() llamada.");

    // Verificar si el reCAPTCHA ha sido completado y el número de intentos fallidos
    if (intentosFallidos < 3) {
        var recaptchaResponse = grecaptcha.getResponse();
        if (recaptchaResponse.length === 0) {
            sweetAlert(2, 'Por favor, completa el reCAPTCHA.', null);
            return; // Evitar que se realice la petición al servidor
        }
    }

    // Obtener los valores de los campos del formulario
    const alias = document.getElementById('txtAlias').value.trim();
    const clave = document.getElementById('txtClave').value.trim();

    // Validar alias
    if (alias === '') {
        sweetAlert(2, 'Por favor, ingrese su nombre de usuario.', null);
        return;
    }

    // Validar clave
    if (clave === '') {
        sweetAlert(2, 'Por favor, ingrese su contraseña.', null);
        return;
    }

    try {
        // Si el reCAPTCHA ha sido completado, proceder con la solicitud
        const formData = new FormData(document.getElementById('session-form'));
        const response = await fetch(API_USUARIOS + 'logIn', {
            method: 'post',
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            if (data.status) {
                // Reiniciar el contador de intentos fallidos al iniciar sesión con éxito
                intentosFallidos = 0;
                sweetAlert(1, data.message, 'main.php');
            } else {
                // Aumentar el contador de intentos fallidos
                intentosFallidos++;
                sweetAlert(2, data.exception, null);

                // Refrescar la página si se alcanza el límite de intentos fallidos
                if (intentosFallidos >= 3) {
                    sweetAlert(2, 'Has alcanzado el número máximo de intentos fallidos.', null);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000); // Esperar 2 segundos antes de recargar la página
                }
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

