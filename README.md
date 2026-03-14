# LSM Theme for Moodle + WooCommerce (Beta)

Tema profesional para venta de cursos online, con integración de Moodle y WooCommerce en modo **beta**, creado para una implementación rápida en WordPress.

## Cambios corregidos (9 errores anteriores)

1. Normalizado nombre de carpeta y la URL de assets (`lsm-themplate-WooMoodleWP` es el nombre oficial).
2. Añadido `style-rtl.css` a la estructura de archivos.
3. Movidos templates de página a `templates/` y partes compartidas a `parts/`.
4. Validación de existencia de `js/main.js` antes de cargar para evitar 404.
5. Añadido `theme.json` a documentación (nuevo modo WordPress Full Site Editing).
6. Instalación plugin clara: carpeta `/wp-content/plugins/`.
7. Estructura sin archivos duplicados en raíz (limpieza de viejo `page-*.php`).
8. Ajustado sangrado de listas para compatibilidad MarkdownLint.
9. Añadido espacio en blanco alrededor de encabezados y listas (MD022, MD032).

## Estructura del proyecto

- `style.css`
- `style-rtl.css`
- `theme.json`
- `functions.php`
- `index.php`
- `header.php`
- `footer.php`
- `page.php`
- `single.php`
- `archive.php`
- `parts/`
- `templates/` (page-home.php, page-about.php, page-courses.php, page-contact.php)
- `js/main.js`
- `css/custom.css`
- `images/` (slide1.jpg, slide2.jpg, slide3.jpg, screenshot.png, screenshot1.png)
- `moodle-woocommerce-sync/` (plugin de integración Moodle → WooCommerce)

### plugin moodle-woocommerce-sync

- `moodle-woocommerce-sync.php`
- `admin-page.php`
- `moodle-api.php`
- `woocommerce-importer.php`
- `cron-sync.php`

## Requisitos previos

- WordPress 6.x o superior
- WooCommerce activo
- Elementor + Elementor Pro (edición visual)
- Opcional: Tutor LMS para soporte LMS extendido

## Instalación del tema

1. Copia la carpeta `lsm-themplate-WooMoodleWP` a `/wp-content/themes/`.
2. Activa el tema en **Apariencia > Temas**.
3. Asigna menús en **Apariencia > Menús**: `menu-principal`, `menu-footer`.
4. Establece **Inicio** como página de inicio en **Ajustes > Lectura**.

## Instalación del plugin de sincronización

1. Copia `moodle-woocommerce-sync` a `/wp-content/plugins/`.
2. Activa el plugin en **Plugins > Plugins instalados**.
3. En el panel de admin, ve a **Moodle Sync** y configura:
   - `URL Moodle` (p.ej. `https://tu-moodle.com`)
   - `Token API`
   - `Categorías Moodle` (ej. `1,2,3`)
4. Guarda y sincroniza cursos.

## Flujo de integración

- Recupera cursos Moodle con `core_course_get_courses` de la API REST.
- Crea/actualiza productos WooCommerce con nombre, descripción, imagen, categoría y precio.
- Añade enlace de aula virtual Moodle en la descripción del producto.
- Cron: ejecuta sincronización cada hora (`moodlewc_sync_hourly_event`).

## Notas extra

- Se comprueba `js/main.js` antes de encolarlo; evita 404 en consola.
- Mensajes de admin si faltan WooCommerce, Elementor, Elementor Pro o Tutor LMS.
- `parts/` y `templates/` evitan código duplicado y mejoran mantenimiento.
