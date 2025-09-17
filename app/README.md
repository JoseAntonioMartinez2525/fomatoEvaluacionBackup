# ℹ️ Requisitos de Despliegue para el Sistema de Evaluación Docente

Este documento detalla los requisitos del entorno de servidor y los pasos necesarios para desplegar la aplicación "Sistema de Evaluación Docente".

## 1. Información General del Proyecto

* **Framework de Desarrollo:** Laravel (versión 12)
* **Lenguaje Principal:** PHP
* **Gestor de Dependencias PHP:** Composer
* **Gestor de Dependencias Frontend:** Node.js y NPM (o Yarn)

## 2. Requisitos del Servidor

Asegúrese de que el servidor cumpla con las siguientes especificaciones:

### 2.1. Sistema Operativo
* Preferentemente: Ubuntu Server 22.04 LTS (o similar distribución basada en Debian/Ubuntu).

### 2.2. Servidor Web
* Apache (con módulo `mod_rewrite` habilitado) o Nginx.
* **Configuración:** La raíz del documento (Document Root) para el dominio de la aplicación debe apuntar a la carpeta `public` del proyecto Laravel. (Ejemplo de configuración Nginx/Apache proporcionada en la carpeta `.` o `docs/`).

### 2.3. PHP
* Versión mínima: PHP 8.3 (Laravel 12.x requiere PHP >= 8.3).
* **Extensiones PHP requeridas (confirmadas por `composer show`):**
    * `bcmath` (Utilizada para operaciones aritméticas de precisión arbitraria)
    * `ctype` (Para funciones de clasificación de caracteres)
    * `curl` (Para realizar solicitudes HTTP a servicios externos, como CloudConvert)
    * `dom` (Crucial para la manipulación de documentos HTML/XML y la generación de PDFs con Dompdf)
    * `fileinfo` (Para la detección de tipos MIME de archivos, por ejemplo, al subir archivos)
    * `filter` (Para el filtrado de datos)
    * `gd` (Requerida por Intervention/Image para la manipulación de imágenes. Si se usa ImageMagick, se necesitaría la extensión `imagick` en su lugar.)
    * `json` (Para la codificación y decodificación de datos JSON)
    * `mbstring` (Para el manejo de cadenas de caracteres multibyte, esencial para Unicode)
    * `mysqlnd` (Controlador Nativo de MySQL para PHP, recomendado para `pdo_mysql`)
    * `openssl` (Para criptografía y conexiones seguras)
    * `pdo_mysql` (Extensión PHP Data Objects para conexiones a bases de datos MySQL)
    * `tokenizer` (Utilizada para el análisis de código PHP)
    * `xml` (Para el análisis y manipulación de documentos XML)
    * `zip` (Si la aplicación maneja archivos ZIP)
    * `exif` (Si Intervention/Image necesita leer datos EXIF de imágenes)

### 2.4. Base de Datos
* **Tipo:** MySQL (versión 8.0 o superior recomendada).
* **Nombre de la Base de Datos:** `evaluacion`
* **Herramienta de Gestión:** phpMyAdmin (si bien la aplicación se conecta directamente, esta herramienta puede ser usada para la gestión manual de la base de datos).
* **Acceso para la Aplicación:** Se necesita un usuario de base de datos dedicado para la conexión de la aplicación.
    * **Nombre de Usuario:** `secretaria`
    * **Contraseña:** (Actualmente vacía, **se recomienda encarecidamente establecer una contraseña robusta para entornos de producción por seguridad.**)
    * **Privilegios Requeridos para el Usuario `secretaria`:** `SELECT`, `INSERT`, `UPDATE`, `DELETE`, `CREATE`, `ALTER`, `INDEX` (además de `CREATE VIEW`, `SHOW VIEW`, `EXECUTE`, `TRIGGER`, `EVENT` si este usuario también se usará para tareas de desarrollo/administración de BD).

### 2.5. Node.js y NPM
* Versión mínima: Node.js v18+
* NPM (viene con Node.js) o Yarn.

### 2.6. `wkhtmltopdf`
* Este es un programa externo que la aplicación utiliza para la generación de PDFs a partir de HTML (vía `barryvdh/laravel-snappy`).
* Debe estar instalado en el servidor.
* **Versión recomendada:** 0.12.6 o superior.
* **Ruta esperada del ejecutable:** `/usr/local/bin/wkhtmltopdf` (o verificar la ruta exacta de instalación en el servidor).

## 3. Pasos de Despliegue 

Estos son los pasos generales que se espera que el equipo de IT siga para el despliegue del código:

1.  **Clonar o Copiar el Repositorio:**
    `git clone <URL_del_repositorio_Git> /ruta/a/tu/aplicacion`
    (O copiar el contenido del archivo `zip` en la carpeta `/ruta/a/tu/aplicacion`)

2.  **Configuración del `.env`:**
    * Copiar `.env.example` a `.env`: `cp .env.example .env`
    * Editar el archivo `.env` con las credenciales y URLs específicas del entorno de producción/staging (APP_URL, DB_*, MAIL_*, etc.).

3.  **Instalar Dependencias de PHP:**
    `composer install --no-dev --optimize-autoloader`

4.  **Instalar y Compilar Dependencias de Frontend:**
    `npm install`
    `npm run build`

5.  **Generar la `APP_KEY` de Laravel (si no está en el `.env` o es la primera vez):**
    `php artisan key:generate`

6.  **Ejecutar Migraciones de Base de Datos:**
    `php artisan migrate --force`
    (Si se usan seeders para datos iniciales: `php artisan db:seed`)

7.  **Establecer Permisos de Archivos:**
    Asegurar que los directorios `storage` y `bootstrap/cache` sean escribibles por el usuario del servidor web.
    `sudo chown -R www-data:www-data /ruta/a/tu/aplicacion`
    `sudo chmod -R 775 /ruta/a/tu/aplicacion/storage /ruta/a/tu/aplicacion/bootstrap/cache`

8.  **Limpiar Cachés de Laravel:**
    `php artisan config:cache`
    `php artisan route:cache`
    `php artisan view:cache`
    `php artisan event:cache`

9.  **Configurar el Servidor Web (Apache/Nginx):**
    Asegurarse de que el Virtual Host apunta a `/ruta/a/tu/aplicacion/public`.

10. **Reiniciar el Servidor Web/PHP-FPM:**
    `sudo systemctl restart apache2` (o `nginx`)
    `sudo systemctl restart php8.1-fpm` (ajustar la versión de PHP)

## 4. Contacto

Para cualquier duda o problema durante el despliegue, por favor contactar a:
[José Antonio martínez del Toro]
[joma_18@alu.uabcs.mx]

## 5 Documentación Adicional

Para una comprensión más profunda del sistema y su operación, consulte los siguientes recursos:

* **Identificadores para la Base de Datos (SQL): en modo Producción** [
    usuario: incogni1_formato_docente
    contraseña:Formato_Docente
]

* **Administrador de archivos: en modo Produccion** [incognitoarquitectura.com](https://www.incognitoarquitectura.com:52229/cpsess7797644560/frontend/jupiter/filemanager/index.html)

* **Hosting temporal del sistema**
([https://incognitoarquitectura.com/])

* **Base de Datos: en modo Producción** [phpMyAdmin](https://www.incognitoarquitectura.com:52229/cpsess7797644560/3rdparty/phpMyAdmin/index.php?route=/sql&pos=0&db=incogni1_formato_docente&table=dictaminators_response_form3_12)

* **Presentación del Proyecto (PowerPoint):** [Ver Presentación General del Sistema](https://drive.google.com/drive/folders/1Ex0b_5KNvih1n3aIxfc4zKocpvNtfn8E?usp=sharing)

* **Base de datos del Proyecto (SQL): en modo local** [Ver Base de datos](https://drive.google.com/file/d/1XQU2OWx69FWvAmLJk1gIEleSjHaqSdrG/view?usp=drive_link)

* **Base de datos con datos de prueba, del Proyecto (SQL): en modo local** [Ver Base de datos de Pruebas](https://drive.google.com/file/d/1_gmt3IKNhVWch0oVGoOkS5R9zxm-z-oq/view?usp=drive_link)
