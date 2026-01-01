# IberPiso - Portal Inmobiliario

Sistema completo de gestión inmobiliaria desarrollado en vanilla PHP, MySQL, HTML5, CSS3 y JavaScript.

## Tecnologías utilizadas

- **Backend**: PHP 8+ (procedural, sin frameworks)
- **Base de datos**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (ES6)
- **Sin dependencias externas**: No usa Composer, npm, CDN, frameworks ni librerías

## Estructura del proyecto

```
src/
├── backend/
│   ├── config.php              # Configuración de base de datos (PDO)
│   ├── index.php               # Punto de entrada único con routing
│   ├── controllers/            # Controladores (Auth, Property, User, Blog, Message)
│   ├── models/                 # Modelos (User, Property, Image, Blog, Message, Favorito)
│   ├── helpers/                # Funciones auxiliares (seguridad, validación)
│   └── database/
│       └── init.sql            # Schema SQL completo
├── frontend/
│   ├── views/                  # Vistas PHP
│   │   ├── home.php
│   │   ├── properties/
│   │   ├── blog/
│   │   ├── admin/
│   │   └── user/
│   └── components/             # Componentes reutilizables
│       ├── Layout/             # Nav, Hero, Footer
│       ├── login-page/
│       └── register-page/
└── public/
    ├── css/                    # Estilos CSS
    ├── js/                     # JavaScript
    ├── images/                 # Imágenes estáticas
    └── uploads/                # Archivos subidos (propiedades, blog)
        ├── properties/
        └── blog/
```

## Instalación

### 1. Requisitos previos

- PHP 8.0 o superior
- MySQL 5.7+ o MariaDB 10.3+
- Servidor web (Apache/Nginx) con soporte PHP

### 2. Configurar base de datos

```sql
-- Crear base de datos
CREATE DATABASE iberpiso CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Importar schema
SOURCE src/backend/database/init.sql;
```

O ejecutar directamente: `mysql -u root -p < src/backend/database/init.sql`

### 3. Configurar credenciales

Editar `src/backend/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'iberpiso');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Configurar servidor web

**Apache (con mod_rewrite):**

Crear archivo `.htaccess` en la raíz del proyecto:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /src/backend/index.php [QSA,L]
```

**Nginx:**

```nginx
location / {
    try_files $uri $uri/ /src/backend/index.php?$query_string;
}
```

**PHP Built-in Server (desarrollo):**

```bash
cd src/backend
php -S localhost:8000
```

Acceder a: `http://localhost:8000`

### 5. Permisos de carpetas

```bash
chmod -R 755 src/public/uploads
chown -R www-data:www-data src/public/uploads  # Linux
```

## Usuario por defecto

- **Email**: admin@iberpiso.com
- **Contraseña**: admin123
- **Rol**: Administrador

## Funcionalidades principales

### Autenticación y usuarios
- Registro de usuarios
- Login/Logout con sesiones seguras
- Gestión de perfil
- Control de acceso basado en roles (admin, usuario, agente)
- Hash seguro de contraseñas (password_hash/password_verify)

### Propiedades
- CRUD completo de propiedades
- Subida de múltiples imágenes
- Búsqueda y filtros (tipo, operación, ciudad, precio, habitaciones)
- Contador de visitas
- Propiedades destacadas
- Gestión de favoritos

### Blog
- CRUD de artículos
- Sistema de slugs
- Imágenes destacadas
- Publicación/borrador

### Mensajes
- Formulario de contacto general
- Consultas por propiedad
- Panel de administración de mensajes

### Panel de administración
- Gestión de usuarios
- Gestión de propiedades
- Gestión de mensajes
- Gestión de blog

## Seguridad implementada

- Prepared statements (PDO) para prevenir SQL injection
- Sanitización de entradas (htmlspecialchars)
- Validación de datos en servidor
- Control de acceso por roles
- Validación de tipos de archivo en subidas
- Límite de tamaño de archivos
- Hash seguro de contraseñas
- Sesiones con configuración segura

## Routing

El sistema usa un único punto de entrada (`src/backend/index.php`) con routing manual:

```
/?action=properties              # Lista de propiedades
/?action=property_detail&id=1    # Detalle de propiedad
/?action=property_create         # Crear propiedad
/?action=login                   # Login
/?action=register                # Registro
/?action=blog                    # Lista de blog
/?action=contact                 # Contacto
/?action=admin_dashboard         # Panel admin
```

## Arquitectura

**Separación de responsabilidades:**

- **Models**: Lógica de base de datos (una tabla = un modelo)
- **Controllers**: Validación, lógica de negocio, decisión de vistas
- **Views**: Solo presentación (sin lógica de BD ni negocio)
- **Helpers**: Funciones reutilizables (seguridad, validación, utilidades)

No hay ORM, no hay frameworks, no hay abstracciones innecesarias.
Solo PHP vanilla profesional con arquitectura clara.

## Base de datos

Tablas principales:

- `rol`: Roles de usuario
- `usuario`: Usuarios del sistema
- `propiedad`: Propiedades inmobiliarias
- `imagen`: Imágenes de propiedades
- `favoritos`: Favoritos de usuarios
- `mensaje`: Mensajes de contacto
- `blog`: Artículos del blog

Todas las tablas incluyen:
- Claves foráneas con CASCADE/RESTRICT apropiado
- Índices en campos de búsqueda frecuente
- Timestamps automáticos (created_at, updated_at)

## Desarrollo

El proyecto está listo para trabajar. Estructura completa implementada.

Para extender:
1. Añadir nuevos modelos en `src/backend/models/`
2. Añadir nuevos controladores en `src/backend/controllers/`
3. Registrar rutas en `src/backend/index.php`
4. Crear vistas en `src/frontend/views/`

## Licencia

Proyecto educativo - TFG 2DAW

## Autor

Bruno Cavichia - Iberpiso Real Estate Portal
