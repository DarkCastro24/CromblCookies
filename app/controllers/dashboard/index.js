// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';
let intentosFallidos = 0;

document.addEventListener('DOMContentLoaded', function () {
    // Petición para verificar si existen usuarios.
    fetchData('readAll')
        .then(response => {
            if (!response.status) {
                sweetAlert(3, response.exception, 'register.php');
            }
        })
        .catch(handleError);

    // Añadir evento al botón de inicio de sesión
    document.getElementById('loginButton').addEventListener('click', function (event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario
        iniciarSesion(); // Llamar a la función de inicio de sesión
    });
});

// Función asíncrona para realizar peticiones a la API
async function fetchData(action, method = 'get', body = null) {
    try {
        const response = await fetch(API_USUARIOS + action, { method, body });
        if (!response.ok) throw new Error(response.status);
        return await response.json();
    } catch (error) {
        handleError(error);
    }
}

// Función para manejar errores
function handleError(error) {
    console.error('Error en la solicitud:', error);
    sweetAlert(2, 'Ocurrió un error inesperado. Por favor, inténtalo nuevamente.', null);
}

async function iniciarSesion() {
    console.log("Función iniciarSesion() llamada.");

    // Verificar si el reCAPTCHA ha sido completado y el número de intentos fallidos
    if (intentosFallidos < 3) {
        const recaptchaResponse = grecaptcha.getResponse();
        if (recaptchaResponse.length === 0) {
            sweetAlert(2, 'Por favor, completa el reCAPTCHA.', null);
            return; // Evitar que se realice la petición al servidor
        }
    }

    // Obtener los valores de los campos del formulario
    const alias = sanitizeInput(document.getElementById('txtAlias').value.trim());
    const clave = sanitizeInput(document.getElementById('txtClave').value.trim());

    // Validar alias y clave
    if (alias === '' || clave === '') {
        sweetAlert(2, 'Por favor, complete todos los campos.', null);
        return;
    }

    // Evitar recarga de la página y llamar a la función iniciar sesión
    try {
        const formData = new FormData();
        formData.append('txtAlias', alias);
        formData.append('txtClave', clave);
        const response = await fetchData('logIn', 'post', formData);

        if (response && response.status) {
            // Reiniciar el contador de intentos fallidos al iniciar sesión con éxito
            intentosFallidos = 0;
            sweetAlert(1, 'Inicio de sesión exitoso', 'main.php');
        } else {
            // Aumentar el contador de intentos fallidos
            intentosFallidos++;
            sweetAlert(2, 'Usuario o contraseña incorrectos.', null);

            // Refrescar manualmente si se alcanza el límite de intentos fallidos
            if (intentosFallidos > 3) {
                sweetAlert(2, 'Alcanzaste el número máximo de intentos fallidos.', null);
                setTimeout(() => {
                    window.location.reload();
                }, 5000); // Esperar 5 segundos antes de recargar la página
            }
        }
    } catch (error) {
        handleError(error);
    }
}

// Función para sanitizar la entrada del usuario (evitar XSS)
function sanitizeInput(input) {
    return input.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#x27;");
}
