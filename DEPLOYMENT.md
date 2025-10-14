# 🚀 Guía de Despliegue - Plantadex Backend

## 📋 Requisitos del Servidor

- **PHP 8.1+**
- **MySQL 8.0+**
- **Composer**
- **Extensiones PHP:** OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo

## 🛠️ Instalación en Producción

### 1. Clonar/Subir Archivos
```bash
# Subir todos los archivos del backend al servidor
```

### 2. Instalar Dependencias
```bash
composer install --optimize-autoloader --no-dev
```

### 3. Configurar Base de Datos
```sql
CREATE DATABASE plantadex CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'plantadex_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON plantadex.* TO 'plantadex_user'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Configurar .env
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.plantadex.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plantadex
DB_USERNAME=plantadex_user
DB_PASSWORD=secure_password

FACEBOOK_CLIENT_ID=your_production_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_production_facebook_app_secret
```

### 5. Generar Clave de Aplicación
```bash
php artisan key:generate
```

### 6. Ejecutar Migraciones
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 7. Configurar Storage
```bash
php artisan storage:link
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 8. Optimizar para Producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🌐 Configuración del Servidor Web

### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Nginx
```nginx
server {
    listen 80;
    server_name api.plantadex.com;
    root /var/www/plantadex-backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔒 Configuración SSL
```bash
# Instalar certificado SSL (Let's Encrypt recomendado)
certbot --nginx -d api.plantadex.com
```

## 📊 Monitoreo y Logs
```bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Ver logs del servidor web
tail -f /var/log/nginx/error.log
```

## 🔄 Actualizaciones
```bash
# Proceso de actualización
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🧪 Testing en Producción
```bash
# Verificar que la API funciona
curl https://api.plantadex.com/api/test

# Respuesta esperada:
{
  "message": "Plantadex API funcionando correctamente",
  "version": "1.0.0",
  "developer": "Fix Bit"
}
```

## 📱 URLs de Producción

- **API Base:** `https://api.plantadex.com/api/`
- **Documentación:** `https://api.plantadex.com/docs`
- **Health Check:** `https://api.plantadex.com/api/test`

## 🔐 Seguridad

- Cambiar todas las claves por defecto
- Configurar firewall para permitir solo puertos necesarios
- Mantener PHP y dependencias actualizadas
- Configurar backups automáticos de la base de datos
- Monitorear logs regularmente

---

**Desarrollado por Fix Bit**  
**Soporte:** support@fixbit.com