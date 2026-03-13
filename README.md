# Cursos Online WP (Theme + Moodle WooCommerce Sync)

Tema profesional para venta de cursos online optimizado para WooCommerce, Elementor, Moodle y e-learning.

## Estructura del proyecto

- `style.css`
- `functions.php`
- `index.php`
- `header.php`
- `footer.php`
- `single.php`
- `page.php`
- `archive.php`
- `moodle-woocommerce-sync/` (plugin de integración Moodle -> WooCommerce) 
   - `moodle-woocommerce-sync.php`
   - `admin-page.php`
   - `moodle-api.php`
   - `woocommerce-importer.php`
   - `cron-sync.php`

## Requisitos previos

- WordPress 6.x o superior
- WooCommerce instalado y activado
- Elementor Pro (Elementor + Elementor Pro) para edición visual
- Si quieres, Tutor LMS (opcional) para soporte LMS adicional

## Instalación del tema

1. Copia la carpeta completa `lsm-themplate-WooMoodleWP` a `/wp-content/themes/`.
2. Activa el tema desde Apariencia > Temas.
3. En el panel de administración, ve a Apariencia > Menús y asigna `menu-principal` y `menu-footer`.

## Instalación del plugin de sincronización

1. Copia la carpeta `moodle-woocommerce-sync` a `/wp-content/plugins/`.
2. Activa el plugin desde Plugins > Plugins instalados.
3. Ve a Moodle Sync en el menú de administración y completa:
   - `URL Moodle` (p.ej. `https://tu-moodle.com`)
   - `Token API` (Web Service token)
   - `Categorías Moodle` (separadas por coma, p.ej. `1,2,3`)
4. Guarda y luego selecciona los cursos detectados para importarlos a WooCommerce.

## Flujo de integración

- El plugin obtiene cursos desde Moodle a través de la API REST `core_course_get_courses`.
- Crea/actualiza productos WooCommerce con:
  - Nombre del curso, descripción, imagen, categoría, precio y enlace Moodle.
- Agrega enlace de acceso Moodle dentro de la descripción del producto.
- Cron interno: sincroniza cada hora (`moodlewc_sync_hourly_event`).

## Otras notas

- El tema incluye comprobación de conectados (WooCommerce, Elementor, Elementor Pro y Tutor LMS) y mostrará aviso en backend si faltan plugins.
- Ajusta `moodle-api.php` si necesitas más campos de Moodle.
- Se recomienda usar `Elementor Pro` para diseñar header/footer y plantillas de curso como `Single Product`.
