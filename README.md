Plantdex Backend API

Backend desarrollado por Fix Bit para la aplicación móvil Plantdex.

Características

- Autenticación completa** (tradicional + Facebook)
- CRUD de plantas** con identificación automática
- Sistema de ranking** global
- Logros y gamificación**
- API RESTful** con Laravel Sanctum
- Base de datos MySQL**

Endpoints Principales

Autenticación
- `POST /api/register` - Registro de usuario
- `POST /api/login` - Login tradicional
- `POST /api/facebook-login` - Login con Facebook
- `POST /api/logout` - Cerrar sesión
- `GET /api/me` - Información del usuario

Plantas
- `GET /api/plants` - Listar plantas del usuario
- `POST /api/plants` - Crear nueva planta
- `GET /api/plants/{id}` - Ver planta específica
- `PUT /api/plants/{id}` - Actualizar planta
- `DELETE /api/plants/{id}` - Eliminar planta
- `POST /api/identify` - Identificar planta por imagen

Ranking
- `GET /api/ranking` - Top 30 usuarios
- `GET /api/my-rank` - Ranking del usuario actual
- `GET /api/stats` - Estadísticas globales

Instalación

1. Instalar dependencias:
   ```bash
   composer install
   ```

2. Configurar base de datos:
   - Crear base de datos MySQL llamada `plantadex`
   - Configurar `.env` con credenciales

3. Ejecutar migraciones:
   ```bash
   php artisan migrate --seed
   ```

   *Crear storage link:
   ```bash
   php artisan storage:link
   ```

5. Iniciar servidor:
   ```bash
   php artisan serve
   ```

Configuración

Variables de entorno (.env)
```env
DB_DATABASE=plantadex
DB_USERNAME=root
DB_PASSWORD=

FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
```

Base de Datos

Tablas principales:
- `users` - Usuarios registrados
- `plants` - Plantas capturadas
- `achievements` - Logros disponibles
- `user_achievements` - Logros desbloqueados

Autenticación

Utiliza **Laravel Sanctum** para autenticación API con tokens.

Headers requeridos:
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

Integración con App Móvil

El backend está diseñado para integrarse perfectamente con la aplicación Flutter de Plantadex.

Ejemplo de uso:
```dart
// En Flutter
final response = await http.get(
  Uri.parse('$baseUrl/api/ranking'),
  headers: {
    'Authorization': 'Bearer $token',
    'Accept': 'application/json',
  },
);
```

Este backend forma parte del ecosistema Plantdex desarrollado por Fix Bit.

---
version Alfa 1.0
