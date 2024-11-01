// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';
let intentosFallidos = 0;

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    console.log("Documento cargado correctamente.");

    // Petición para verificar si existen usuarios.
    fetch(API_USUARIOS + 'readAll', {
        method: 'get'
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    sweetAlert(3, response.message, 'index.php');
                } else {
                    sweetAlert(4, 'Debe crear un usuario para comenzar', null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });

    // Añadir evento al botón de registro
    document.querySelector('.botonLogin').addEventListener('click', function (event) {
        event.preventDefault(); // Evitar el comportamiento predeterminado del formulario
        registrarUsuario(); // Llamar a la función de registro
    });

    // Aplicar máscara al campo DUI
    document.getElementById('txtDui').addEventListener('input', function () {
        let dui = this.value.replace(/\D/g, ''); // Eliminar todos los caracteres no numéricos
        if (dui.length > 8) {
            dui = dui.substring(0, 8) + '-' + dui.substring(8, 9);
        }
        this.value = dui;
    });

    // Aplicar máscara al campo Teléfono
    document.getElementById('txtTelefono').addEventListener('input', function () {
        let telefono = this.value.replace(/\D/g, ''); // Eliminar todos los caracteres no numéricos
        if (telefono.length > 4) {
            telefono = telefono.substring(0, 4) + '-' + telefono.substring(4, 8);
        }
        this.value = telefono;
    });
});

async function registrarUsuario() {
    console.log("Función registrarUsuario() llamada.");

    // Obtener los valores de los campos del formulario
    const alias = document.getElementById('txtAlias').value.trim();
    const dui = document.getElementById('txtDui').value.trim();
    const correo = document.getElementById('txtCorreo').value.trim();
    const telefono = document.getElementById('txtTelefono').value.trim();
    const clave1 = document.getElementById('txtClave1').value;
    const clave2 = document.getElementById('txtClave2').value;

    // Verificar si el reCAPTCHA ha sido completado y el número de intentos fallidos
    if (intentosFallidos < 3) {
        var recaptchaResponse = grecaptcha.getResponse();
        if (recaptchaResponse.length === 0) {
            sweetAlert(2, 'Por favor, completa el reCAPTCHA.', null);
            return; // Evitar que se realice la petición al servidor
        }
    }

    // Validar alias
    if (alias === '') {
        sweetAlert(2, 'Por favor, ingrese un alias.', null);
        return;
    }

    // Validar DUI (debe ser en formato XXXXXXXX-X)
    const duiRegex = /^\d{8}-\d{1}$/;
    if (!duiRegex.test(dui)) {
        sweetAlert(2, 'El DUI debe tener el formato XXXXXXXX-X.', null);
        return;
    }

    // Validar correo electrónico (debe tener un @ y un dominio)
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(correo)) {
        sweetAlert(2, 'Por favor, ingrese un correo electrónico válido.', null);
        return;
    }

    // Validar teléfono (debe ser en formato XXXX-XXXX)
    const telefonoRegex = /^\d{4}-\d{4}$/;
    if (!telefonoRegex.test(telefono)) {
        sweetAlert(2, 'El teléfono debe tener el formato XXXX-XXXX.', null);
        return;
    }

    // Validar contraseña (mínimo 8 caracteres, al menos una mayúscula, un número y un carácter especial)
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(clave1)) {
        sweetAlert(2, 'La contraseña debe tener al menos 8 caracteres, incluyendo una letra mayúscula, un número y un carácter especial.', null);
        return;
    }

    // Validar confirmación de contraseña
    if (clave1 !== clave2) {
        sweetAlert(2, 'Las contraseñas no coinciden.', null);
        return;
    }

    try {
        // Si el reCAPTCHA ha sido completado, proceder con la solicitud
        const formData = new FormData(document.getElementById('register-form'));
        const response = await fetch(API_USUARIOS + 'register', {
            method: 'post',
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            if (data.status) {
                // Reiniciar el contador de intentos fallidos al registrarse con éxito
                intentosFallidos = 0;
                sweetAlert(1, data.message, 'index.php');
            } else {
                // Aumentar el contador de intentos fallidos
                intentosFallidos++;
                sweetAlert(2, data.exception, null);

                // Refrescar la página si se alcanza el límite de intentos fallidos
                if (intentosFallidos >= 3) {
                    sweetAlert(2, 'Has alcanzado el número máximo de intentos fallidos. Recargando la página...', null);
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
