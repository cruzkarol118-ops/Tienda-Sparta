# Spartan Ecommerce (Laravel)

Guía rápida para correr el proyecto en local usando PostgreSQL con Docker.

## Requisitos

- PHP 8.0+
- Composer
- Node.js + npm
- Docker + Docker Compose

## 1) Clonar e instalar dependencias

```bash
composer install
npm install
```

## 2) Configurar variables de entorno

Si no tienes `.env`:

```bash
cp .env.example .env
```

Edita `.env` y asegúrate de tener:

```env
APP_NAME=Spartan
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=spartan
DB_USERNAME=admin
DB_PASSWORD=admin
```

## 3) Levantar PostgreSQL con Docker

```bash
docker compose up -d
```

Verifica que esté arriba:

```bash
docker ps
```

## 4) Preparar Laravel

```bash
php artisan key:generate
php artisan config:clear
php artisan cache:clear
```

## 5) Ejecutar migraciones

```bash
php artisan migrate
```

## 6) Compilar assets

Para desarrollo:

```bash
npm run dev
```

Para producción:

```bash
npm run prod
```

## 7) Levantar servidor Laravel

En otra terminal:

```bash
php artisan serve
```

Abre en navegador:

`http://127.0.0.1:8000`

---

## Flujo recomendado (3 terminales)

1. Terminal A: `docker compose up -d`
2. Terminal B: `npm run dev` no hay necesidad de este paso pero si no te sirve lo debes correr
3. Terminal C: `php artisan serve`

---

## Solución de errores comunes

- Error de conexión DB (`SQLSTATE[HY000] [2002]`): revisa credenciales en `.env`, confirma contenedor arriba con `docker ps`, y limpia caché con `php artisan config:clear`.
- Error de columna faltante (ej. `priority does not exist`): ejecuta `php artisan migrate` para aplicar migraciones nuevas.
- Si cambias `.env`, vuelve a correr `php artisan config:clear`.
