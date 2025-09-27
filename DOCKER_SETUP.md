# 🐳 Docker Setup Instructions

## Quick Start

### 1. Prerequisites
- Docker Engine 20.10+
- Docker Compose 2.0+
- Git

### 2. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Edit .env file with Docker-specific values:
# DB_HOST=mysql
# REDIS_HOST=redis
# APP_URL=http://localhost:8000
```

### 3. Deploy Development Environment
```bash
# Make deploy script executable
chmod +x docker/deploy.sh

# Deploy development environment
./docker/deploy.sh dev
```

### 4. Access Your Application
- **Application**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
- **Health Check**: http://localhost:8000/health

## Production Deployment

### 1. Configure Production Environment
```bash
# Edit .env file with production values:
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
DB_ROOT_PASSWORD=your_root_password
REDIS_PASSWORD=your_redis_password
DOMAIN=yourdomain.com
ACME_EMAIL=your@email.com
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Deploy Production Environment
```bash
./docker/deploy.sh prod
```

### 3. Access Production Application
- **Application**: https://yourdomain.com
- **Traefik Dashboard**: http://localhost:8080

## Docker Commands

### Development
```bash
# Start development environment
docker-compose up -d

# View logs
docker-compose logs -f

# Access application shell
docker-compose exec app bash

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app composer install
```

### Production
```bash
# Start production environment
docker-compose -f docker-compose.prod.yml up -d

# View logs
docker-compose -f docker-compose.prod.yml logs -f

# Access application shell
docker-compose -f docker-compose.prod.yml exec app bash
```

## Services Included

### Development Environment
- **Laravel App**: Development server on port 8000
- **MySQL**: Database server on port 3306
- **Redis**: Cache server on port 6379
- **phpMyAdmin**: Database management on port 8080

### Production Environment
- **Laravel App**: Nginx + PHP-FPM with optimized settings
- **MySQL**: Production-tuned database
- **Redis**: Password-protected cache server
- **Traefik**: Reverse proxy with SSL termination

## Troubleshooting

### Common Issues

1. **Port conflicts**: Change ports in docker-compose.yml
2. **Permission errors**: Run `chmod +x docker/deploy.sh`
3. **Database connection**: Check DB_HOST=mysql in .env
4. **Build failures**: Run `docker-compose build --no-cache`

### Debug Commands
```bash
# Check container status
docker-compose ps

# View detailed logs
docker-compose logs app

# Access container shell
docker-compose exec app bash

# Check PHP configuration
docker-compose exec app php -i

# Test database connection
docker-compose exec app php artisan tinker
```

## File Structure

```
docker/
├── deploy.sh              # Deployment script
├── mysql/
│   ├── my.cnf            # MySQL configuration
│   └── init.sql          # Database initialization
├── nginx/
│   ├── nginx.conf        # Nginx main configuration
│   └── default.conf      # Laravel site configuration
├── php/
│   ├── php.ini          # Production PHP settings
│   ├── php-dev.ini      # Development PHP settings
│   └── opcache.ini      # OPcache configuration
└── supervisor/
    └── supervisord.conf  # Process management
```

## Security Features

- **Non-root user**: Application runs as `appuser`
- **Security headers**: X-Frame-Options, CSP, etc.
- **SSL/TLS**: Automatic Let's Encrypt certificates
- **Database security**: Separate users and passwords
- **Redis authentication**: Password-protected Redis
- **File permissions**: Proper ownership and permissions

## Performance Optimizations

- **Multi-stage builds**: Optimized image sizes
- **OPcache**: Bytecode caching enabled
- **Gzip compression**: Static assets compressed
- **Asset caching**: Long-term cache headers
- **Database tuning**: Optimized MySQL configuration
- **Redis caching**: Session and cache storage
- **Queue workers**: Background job processing

---

**Your Collectorate Library is now Dockerized and ready for deployment! 🚀**
