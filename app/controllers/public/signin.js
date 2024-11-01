// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENTES = '../../app/api/public/clientes.php?action=';
let intentosFallidos = 0;

document.addEventListener('DOMContentLoaded', function () {
    // Añadir evento para el botón de iniciar sesión
    document.getElementById('btn-login').addEventListener('click', function () {
        iniciarSesion();
    });

    // Añadir evento para el formulario para que funcione con Enter
    document.getElementById('session-form').addEventListener('submit', function (event) {
        event.preventDefault(); // Evitar recarga de la página
        iniciarSesion();
    });

    // Añadir evento para detectar la tecla Enter en el formulario
    document.getElementById('session-form').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Evitar el envío predeterminado del formulario
            iniciarSesion();
        }
    });
});

async function iniciarSesion() {
    // Verificar si el reCAPTCHA ha sido completado y el número de intentos fallidos
    if (intentosFallidos < 3) {
        var recaptchaResponse = grecaptcha.getResponse();
        if (recaptchaResponse.length === 0) {
            // Si el reCAPTCHA no se ha completado, mostrar un mensaje de error
            sweetAlert(2, 'Por favor, completa el reCAPTCHA.', null);
            return; // Evitar que se realice la petición al servidor
        }
    }

    try {
        // Si el reCAPTCHA ha sido completado, proceder con la solicitud
        const formData = new FormData(document.getElementById('session-form'));
        const response = await fetch(API_CLIENTES + 'logIn', {
            method: 'post',
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            if (data.status) {
                // Reiniciar el contador de intentos fallidos al iniciar sesión con éxito
                intentosFallidos = 0;
                sweetAlert(1, data.message, 'index.php');
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
