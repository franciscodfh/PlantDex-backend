# 🚀 Configuración con Laragon

## 📋 Pasos para configurar en Laragon

### 1. Mover el proyecto
```
Copia la carpeta plantadex-backend a:
C:\laragon\www\plantadex-backend
```

### 2. Crear base de datos
- Abrir **HeidiSQL** desde Laragon
- Crear nueva base de datos: `plantadex`

### 3. Instalar dependencias
```bash
# En terminal de Laragon:
cd C:\laragon\www\plantadex-backend
composer install
```

### 4. Configurar permisos
```bash
# En terminal de Laragon:
php artisan key:generate
```

### 5. Ejecutar migraciones
```bash
php artisan migrate
php artisan db:seed
```

### 6. Crear storage link
```bash
php artisan storage:link
```

## 🌐 URLs de acceso

- **API Base:** `http://plantadex-backend.test/api/`
- **Test endpoint:** `http://plantadex-backend.test/api/test`

## 🧪 Probar la API

### Test básico:
```
GET http://plantadex-backend.test/api/test
```

### Ranking:
```
GET http://plantadex-backend.test/api/ranking
```

### Registro:
```
POST http://plantadex-backend.test/api/register
Content-Type: application/json

{
    "username": "testuser",
    "email": "test@plantadex.com",
    "password": "password123"
}
```

## 🔧 Configuración Flutter

Actualizar en `backend_service.dart`:
```dart
static const String baseUrl = 'http://plantadex-backend.test/api';
```

¡Listo para usar! 🎉