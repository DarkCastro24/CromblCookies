## Esta demo ha sido desarrollada con las siguientes herramientas:
- XAMPP v8.0.0 (PHP 8.0.0 y Apache 2.4.46)
- PostgreSQL 15 con pgAdmin 4
- Boostrap 5
- Vanilla JS

## Paso 1. Crear una base de datos llamada GameBridge y luego hacer la importación con el archivo database.sql

## Paso 2. Modificar las credenciales necesarias para la conexión con la base de datos (ubicadas en app/helpers/database.php):
    Servidor: localhost (127.0.0.1)
    Usuario: postgres
    Contraseña: 2002
    Puerto por defecto (5432)

## Paso 3. Ingresar al sitio web que se desea visualizar.
    Inicio del sitio privado (al ingresar por primera vez se pedirá crear un usuario):
        localhost/GameBridge/views/dashboard/
    Inicio del sitio público:
        localhost/GameBridge/views/public/

Para que funcione PostgreSQL con XAMPP, es necesario acceder a la dirección C:\xampp\php y hacer lo siguiente:
1. Ubicar y abrir el archvio php.ini
2. Buscar la línea ;extension=pdo_pgsql
3. Borrar el ; que esta al inicio de la línea
4. Guardar los cambios y cerrar el archivo
5. Reiniciar Apache