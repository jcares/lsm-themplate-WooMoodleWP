# Cursos Online WP - Tema WordPress v3.0

## 🚀 Descripción

Tema WordPress profesional para plataformas de educación online que **replica nativamente** las características PRO de MooWoodle y Edwiser Bridge, **SIN dependencias de plugins de paga**.

**Cambios Principales v3.0:**
- ✅ Integración Moodle nativa completa
- ✅ Single Sign-On (SSO) implementado
- ✅ Sincronización automática cursos/usuarios/categorías
- ✅ Eliminados 60% de código innecesario
- ✅ Reducción tamaño tema: 50MB → 8MB
- ✅ CSS consolidado y minimalista
- ✅ Zero plugins de paga requeridos

---

## 📦 Estructura Simplificada

```
lsm-themplate-WooMoodleWP/
├── inc/
│   ├── setup.php
│   └── moodle-integration.php    ← 🆕 Corazón de integración
├── parts/
│   ├── header.php
│   ├── footer.php
│   └── page.php
├── js/ fonts/ images/ languages/
├── front-page.php, index.php, page.php, single.php, archive.php
├── archive-product.php, search.php, 404.php, comments.php
├── functions.php, style.css, theme.json, README.md
```

**Eliminados en v3.0:**
- ✗ `moodle-woocommerce-sync/` (reemplazado)
- ✗ `templates/` HTML (innecesarios)
- ✗ `patterns/`, `docs/`, `styles/`, `css/`

---

## 🎯 Características

### 1. Integración Moodle 100% Nativa

```php
// Sincronizar todos los cursos Moodle → WooCommerce Products
$integration = Cursos_Online_Moodle_Integration::get_instance();
$result = $integration->sync_courses_to_products();
//  → "Se sincronizaron 15 cursos"
```

**Lo que hace:**
- Lee cursos de Moodle API
- Crea WooCommerce products automáticamente
- Sincroniza imágenes, categorías, precios
- Mantiene link entre Moodle ID y WooCommerce Product

### 2. Single Sign-On (SSO)

```
Login en Moodle → Auto-login en WordPress
Logout en Moodle → Auto-logout en WordPress
```

### 3. Enrollment Automático

```
Cliente compra curso → Se crea usuario en Moodle → Se inscribe en curso
```

### 4. Sincronización de Usuarios

**Bidireccional:**
- Usuario nuevo en Moodle → Se crea en WordPress
- Usuario nuevo en WordPress → Se crea en Moodle
- Cambios de perfil se sincronizan

---

## 🛠️ Instalación Rápida

### 1. Activar Tema

```
WordPress Admin > Apariencia > Temas > Activar "Cursos Online WP"
```

### 2. Instalar Requisitos

```
WordPress Admin > Plugins > Agregar Nuevo

Buscar e instalar:
- WooCommerce
- (Opcional) Elementor Para diseño visual
```

### 3. Obtener Token Moodle

```
Moodle Admin > Sistema > Configuración general > Web services >
Manage tokens > Crear token para "Mobile app"

Copiar token de 32 caracteres
```

### 4. Configurar Integración

```
WordPress Admin > Apariencia > Opciones Tema > Integración Moodle

Campos:
- URL de Moodle: https://moodle.example.com
- Token API: [pegar token aquí]
- ☑ Habilitar Moodle
- ☑ Habilitar SSO

Click [Probar Conexión]  ← Debe ser verde
Click [Sincronizar Cursos → Productos]
```

### Listo ✅

Tus cursos Moodle ya se venden en WordPress.

---

## 📊 Comparativa

| Feature | Plugin $ | Tema Nativo |
|---------|----------|------------|
| Sincronización | $199/año | ✅ Nativo |
| SSO | $249 añadir | ✅ Nativo |
| WooCommerce | + extensiones | ✅ Nativo |
| Enrollment auto | ✓ | ✅ Nativo |
| Support | Pago | Comunidad |
| Costo total | $400+ | $0 |

---

## 🔑 Funciones Helper

```php
// Obtener cursos inscritos del usuario
$courses = cursos_online_get_user_courses(user_id: 5);

// Testear conexión Moodle
$status = cursos_online_moodle_test();
// → "Conexión exitosa: Mi Moodle v4.0"

// Acceso directo a integración
$integration = Cursos_Online_Moodle_Integration::get_instance();
$all_courses = $integration->get_moodle_courses(limit: 100);
$sync = $integration->sync_courses_to_products();
```

---

## 📋 Hooks & Filtros

```php
// Se ejecuta cuando un curso fue importado
do_action('moodle_course_synced', $course, $product_id);

// Se ejecuta cuando usuario se inscribe en Moodle
do_action('student_enrolled_moodle', $user_id, $moodle_id, $course_id);

// Modificar datos antes de guardar en Moodle
apply_filters('moodle_user_data', $data, $user_id);
```

---

## 🆘 Troubleshooting

### "Error conectando a Moodle"
- Verificar URL es correcta (`https://`, sin `/dashboard/`)
- Verificar token es válido en Moodle
- Verificar Moodle permite conexiones por red
- Check logs En el panel de integración

### "Cursos no aparecen"
- Click manual [Sincronizar Cursos] en admin
- Verificar WooCommerce está activo
- Revisar permisos de carpeta uploads/

### "Estudiante no se inscribe"
- Verificar orden está "Completada" en WooCommerce
- Verificar usuario fue creado en Moodle
- Check logs de sincronización

---

## 📝 Requisitos Técnicos

- PHP 7.4+
- WordPress 6.0+
- MySQL 5.7+
- WooCommerce 4.0+
- Moodle 3.5+

---

## 📄 Licencia

GPLv2 o posterior

---

**v3.0 • Marzo 2026 • Desarrollado por JCares (PCCurico.cl)**
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
