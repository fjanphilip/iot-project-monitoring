# 🐳 Docker Setup Guide

## Quick Start

### 1. Build dan Start Containers

```bash
docker-compose build
docker-compose up -d
```

### 2. Check Status

```bash
docker-compose ps
```

### 3. View Logs

```bash
docker-compose logs -f app
```

### 4. Test Health Check

```bash
curl http://localhost/health
```

## Useful Commands

```bash
# View logs
docker-compose logs -f app
docker-compose logs -f db

# Run migrations
docker-compose exec app php artisan migrate

# Run Artisan commands
docker-compose exec app php artisan tinker
docker-compose exec app php artisan seed

# Stop containers
docker-compose down

# Restart
docker-compose restart app

# SSH into container
docker-compose exec app bash

# MySQL shell
docker-compose exec db mysql -u laravel -p iot_db
```

## Configuration

### Environment Variables

Edit `.env` file:

```env
APP_ENV=production
APP_DEBUG=false
DB_PASSWORD=laravel_secret
DB_ROOT_PASSWORD=root_secret
```

## Memory Allocation

```
Total VPS: 1GB
├─ PHP-FPM (app):  512 MB
├─ MySQL (db):     256 MB
└─ System/Buffer:  256 MB
```

## Troubleshooting

### Container exits immediately

```bash
docker-compose logs app
```

### Database connection error

```bash
# Wait for database
sleep 30
docker-compose exec app php artisan migrate
```

### Port already in use

Edit `docker-compose.yml` and change port:

```yaml
ports:
    - "8080:80" # Use port 8080 instead
```

---

For detailed documentation, see the project README.
