<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sistema de Gestión de Productos y Categorías - API REST

## 🚀 Descripción
API REST robusta desarrollada con Laravel para gestión de productos y categorías, con implementación de caché Redis y sistema avanzado de control de tráfico.

## ✨ Características Principales
- Autenticación segura mediante Laravel Sanctum
- Sistema de caché con Redis para optimización de rendimiento
- Rate limiting personalizado por tipo de endpoint
- Validaciones robustas con Form Requests
- Transformación de datos con API Resources
- Sistema de logging avanzado
- Paginación optimizada

## 🛠️ Requisitos Técnicos
- PHP 8.1 o superior
- Composer 2.x
- MySQL 5.7+
- Redis Server
- Laravel 10.x

## ⚙️ Configuración del Entorno
1. Variables de entorno esenciales:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_database
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

CACHE_DRIVER=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null

SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SESSION_DOMAIN=localhost
```

## 🔒 Seguridad
- Implementación de CORS configurada
- Validación de tokens mediante Sanctum
- Rate limiting por IP y token
- Encriptación de datos sensibles
- Headers de seguridad configurados

## 📡 Endpoints

### Públicos (60 req/min)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/products` | Lista paginada de productos |
| GET | `/api/products/{id}` | Detalle de producto |
| GET | `/api/categories` | Lista de categorías |

### Autenticados (30 req/min)
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/products` | Crear producto |
| PUT | `/api/products/{id}` | Actualizar producto |
| DELETE | `/api/products/{id}` | Eliminar producto |

## 🚀 Instalación

```bash
# Clonar repositorio
git clone <repositorio>

# Instalar dependencias
composer install

# Configuración inicial
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link

# Generar documentación API (opcional)
php artisan scribe:generate
```

## 🔍 Testing
```bash
php artisan test
```

## 🛠️ Comandos Útiles
```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Actualizar base de datos
php artisan migrate:fresh --seed

# Monitorear logs
tail -f storage/logs/laravel.log
```

# API Documentation

## Autenticación

### 1. Registro de Usuario
```bash
POST /api/v1/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}

# Respuesta exitosa (201):
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "access_token": "1|abcdef...",
        "token_type": "Bearer"
    }
}
```

### 2. Login
```bash
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}

# Respuesta exitosa (200):
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "access_token": "2|xyz123...",
        "token_type": "Bearer"
    }
}
```

### 3. Logout
```bash
POST /api/v1/auth/logout
Authorization: Bearer {token}

# Respuesta exitosa (200):
{
    "success": true,
    "message": "Successfully logged out"
}
```

## Endpoints de Productos

### 1. Listar Productos
```bash
GET /api/v1/products
Authorization: Bearer {token}

# Respuesta:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Producto 1",
            "description": "Descripción del producto",
            "price": 99.99,
            "stock": 100,
            "category": {
                "id": 1,
                "name": "Categoría 1"
            }
        }
    ]
}
```

### 2. Crear Producto
```bash
POST /api/v1/products
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Nuevo Producto",
    "description": "Descripción del producto",
    "price": 99.99,
    "stock": 100,
    "category_id": 1,
    "status": "active"
}

# Respuesta (202):
{
    "success": true,
    "message": "Product creation has been queued for processing"
}
```

### 3. Actualizar Producto
```bash
PUT /api/v1/products/1
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Producto Actualizado",
    "price": 149.99
}

# Respuesta (202):
{
    "success": true,
    "message": "Product update has been queued for processing"
}
```

## Endpoints de Categorías

### 1. Listar Categorías
```bash
GET /api/v1/categories
Authorization: Bearer {token}

# Respuesta:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Categoría 1",
            "description": "Descripción",
            "products_count": 5
        }
    ],
    "from_cache": true
}
```

### 2. Crear Categoría
```bash
POST /api/v1/categories
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Nueva Categoría",
    "description": "Descripción de la categoría",
    "status": "active"
}

# Respuesta (202):
{
    "success": true,
    "message": "Category creation has been queued for processing"
}
```

### 3. Obtener Productos por Categoría
```bash
GET /api/v1/categories/1/products
Authorization: Bearer {token}

# Respuesta:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Producto 1",
            "price": 99.99
        }
    ]
}
```

## Códigos de Estado HTTP

- 200: Éxito
- 201: Creado exitosamente
- 202: Aceptado (procesamiento en cola)
- 401: No autorizado
- 403: Prohibido
- 404: No encontrado
- 422: Error de validación
- 429: Demasiadas peticiones
- 500: Error del servidor

## Headers Requeridos

```bash
# Para todas las peticiones autenticadas:
Authorization: Bearer {tu-token}

# Para peticiones con datos:
Content-Type: application/json
Accept: application/json
```

## Rate Limiting

- Endpoints de lectura: 60 peticiones/minuto
- Endpoints de escritura: 30 peticiones/minuto

## Sistema de Colas

### Colas Disponibles
- `product-creations`: Creación de productos
- `product-updates`: Actualización de productos
- `category-operations`: Operaciones de categorías

### Ejecutar Workers
```bash
# Terminal 1: Servidor API
php artisan serve

# Terminal 2: Worker de Colas
php artisan queue:work --queue=product-creations,product-updates,category-operations
```

## Respuestas Estándar

### Éxito
```json
{
    "success": true,
    "data": {
        // datos
    }
}
```

### Error
```json
{
    "success": false,
    "message": "Mensaje de error",
    "errors": {
        // detalles
    }
}
```

## Configuración

### Requisitos
- PHP 8.1+
- Laravel 10.x
- MySQL/MariaDB
- Composer

### Instalación
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan queue:table
php artisan migrate
```

### Variables de Entorno
```env
QUEUE_CONNECTION=database
AUTH_GUARD=sanctum
```

## Rate Limiting
- Endpoints de lectura: 60 peticiones/minuto
- Endpoints de escritura: 30 peticiones/minuto

## Caché
Los siguientes datos se almacenan en caché:
- Lista de productos (3600s)
- Producto individual (3600s)
- Lista de categorías (3600s)
- Categoría individual (3600s)
- Productos por categoría (3600s)

## Laravel Telescope

### Descripción
Laravel Telescope proporciona información detallada sobre las solicitudes entrantes a tu aplicación, excepciones, entradas de registro, consultas de base de datos, trabajos en cola, correo, notificaciones y más.

### Acceso
```bash
/telescope      # Panel de administración de Telescope
```

### Características Monitoreadas
- Requests y Responses
- Jobs y Queue
- Logs del sistema
- Queries de base de datos
- Cache operations
- Redis commands
- Events & Listeners
- Mail sending
- Notifications
- Gates & Policy checks
- Schedule tasks

### Configuración
```bash
# Instalar Telescope
composer require laravel/telescope

# Publicar assets
php artisan telescope:install

# Ejecutar migraciones
php artisan migrate
```

### Seguridad
El acceso a Telescope está restringido en producción. Para acceder, debes configurar los usuarios autorizados en `app/Providers/TelescopeServiceProvider.php`:

```php
Telescope::filter(function (IncomingEntry $entry) {
    return true; // En desarrollo
});

// Solo usuarios autorizados en producción
Gate::define('viewTelescope', function ($user) {
    return in_array($user->email, [
        'admin@example.com'
    ]);
});
```

### Monitoreo de Cola de Trabajos
Telescope proporciona una interfaz visual para monitorear:
- Jobs despachados
- Jobs completados
- Jobs fallidos
- Tiempo de ejecución
- Excepciones
- Payload de datos

### Monitoreo de Caché
Visualiza en tiempo real:
- Hits y misses de caché
- Claves almacenadas
- Tiempo de expiración
- Operaciones de limpieza

## Desarrollado por Danilo Doria🚀
