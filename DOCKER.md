# 🐳 Docker Deployment Guide

This guide provides comprehensive instructions for deploying the Collectorate Library Management System using Docker.

## 📋 Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+
- Git
- At least 2GB RAM available for containers

## 🚀 Quick Start

### Development Environment

1. **Clone and setup:**
   ```bash
   git clone <your-repo>
   cd collectorate-library
   cp .env.example .env
   ```

2. **Deploy development environment:**
   ```bash
   ./docker/deploy.sh dev
   ```

3. **Access the application:**
   - Application: http://localhost:8000
   - phpMyAdmin: http://localhost:8080

### Production Environment

1. **Configure environment variables:**
   ```bash
   # Edit .env file with production values
   DB_DATABASE=your_database_name
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_secure_password
   DB_ROOT_PASSWORD=your_root_password
   REDIS_PASSWORD=your_redis_password
   DOMAIN=yourdomain.com
   ACME_EMAIL=your@email.com
   ```

2. **Deploy production environment:**
   ```bash
   ./docker/deploy.sh prod
   ```

3. **Access the application:**
   - Application: https://yourdomain.com
   - Traefik Dashboard: http://localhost:8080

## 🏗️ Architecture

### Multi-Stage Dockerfile

The Dockerfile uses a multi-stage build approach:

1. **Assets Stage**: Builds frontend assets (CSS/JS)
2. **Vendor Stage**: Installs PHP dependencies
3. **Production Stage**: Creates optimized production image
4. **Development Stage**: Adds development tools and configurations

### Services

#### Development (`docker-compose.yml`)
- **app**: Laravel application with development tools
- **mysql**: MySQL 8.0 database
- **redis**: Redis cache and session store
- **phpmyadmin**: Database management interface

#### Production (`docker-compose.prod.yml`)
- **app**: Optimized Laravel application with Nginx
- **mysql**: MySQL 8.0 with performance tuning
- **redis**: Redis with authentication
- **traefik**: Reverse proxy with SSL termination

## 🔧 Configuration

### PHP Configuration

- **Production**: `/docker/php/php.ini` - Optimized for performance
- **Development**: `/docker/php/php-dev.ini` - Debug-friendly settings
- **OPcache**: `/docker/php/opcache.ini` - Bytecode caching

### Nginx Configuration

- **Main config**: `/docker/nginx/nginx.conf` - Global settings
- **Site config**: `/docker/nginx/default.conf` - Laravel-specific routing

### MySQL Configuration

- **Performance tuning**: `/docker/mysql/my.cnf`
- **Initialization**: `/docker/mysql/init.sql`

## 📝 Environment Variables

### Required for Production

```bash
# Database
DB_DATABASE=collectorate_library
DB_USERNAME=laravel
DB_PASSWORD=secure_password
DB_ROOT_PASSWORD=root_password

# Redis
REDIS_PASSWORD=redis_password

# Domain & SSL
DOMAIN=yourdomain.com
ACME_EMAIL=your@email.com

# Laravel
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your_app_key
```

### Optional

```bash
# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your_password

# Storage
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

## 🛠️ Management Commands

### Using the Deploy Script

```bash
# Development
./docker/deploy.sh dev

# Production
./docker/deploy.sh prod

# Stop all containers
./docker/deploy.sh stop

# View logs
./docker/deploy.sh logs
./docker/deploy.sh logs prod

# Access shell
./docker/deploy.sh shell
./docker/deploy.sh shell prod
```

### Manual Docker Commands

```bash
# Build images
docker compose build
docker compose -f docker-compose.prod.yml build

# Start services
docker compose up -d
docker compose -f docker-compose.prod.yml up -d

# View logs
docker compose logs -f app
docker compose -f docker-compose.prod.yml logs -f app

# Execute commands
docker compose exec app php artisan migrate
docker compose exec app composer install

# Access containers
docker compose exec app bash
docker compose exec mysql mysql -u laravel -p
```

## 🔒 Security Features

### Production Security

- **Non-root user**: Application runs as `appuser`
- **Security headers**: X-Frame-Options, CSP, etc.
- **SSL/TLS**: Automatic Let's Encrypt certificates
- **Database security**: Separate users and passwords
- **Redis authentication**: Password-protected Redis
- **File permissions**: Proper ownership and permissions

### Development Security

- **Relaxed CSP**: Allows Vite dev server
- **Debug mode**: Enabled for development
- **Local access**: No external restrictions

## 📊 Monitoring & Logs

### Log Locations

- **Application logs**: `/var/log/supervisor/`
- **Nginx logs**: `/var/log/nginx/`
- **PHP logs**: `/var/log/php_errors.log`
- **MySQL logs**: Available via phpMyAdmin

### Health Checks

- **Application**: `http://localhost/health`
- **Database**: Connection via phpMyAdmin
- **Redis**: Connection via application

## 🚀 Performance Optimizations

### Production Optimizations

- **OPcache**: Bytecode caching enabled
- **Gzip compression**: Static assets compressed
- **Asset caching**: Long-term cache headers
- **Database tuning**: Optimized MySQL configuration
- **Redis caching**: Session and cache storage
- **Queue workers**: Background job processing

### Resource Limits

- **Memory**: 256M PHP memory limit
- **Upload**: 50M file upload limit
- **Connections**: 200 max MySQL connections
- **Workers**: 2 queue workers

## 🔄 Updates & Maintenance

### Updating the Application

```bash
# Pull latest changes
git pull origin main

# Rebuild and restart
./docker/deploy.sh prod
```

### Database Backups

```bash
# Create backup
docker compose exec mysql mysqldump -u root -p collectorate_library > backup.sql

# Restore backup
docker compose exec -T mysql mysql -u root -p collectorate_library < backup.sql
```

### Log Rotation

```bash
# Clean old logs
docker compose exec app find /var/log -name "*.log" -mtime +7 -delete
```

## 🐛 Troubleshooting

### Common Issues

1. **Port conflicts**: Change ports in docker-compose.yml
2. **Permission errors**: Check file ownership
3. **Database connection**: Verify environment variables
4. **SSL issues**: Check domain configuration

### Debug Commands

```bash
# Check container status
docker compose ps

# View detailed logs
docker compose logs app

# Access container shell
docker compose exec app bash

# Check PHP configuration
docker compose exec app php -i

# Test database connection
docker compose exec app php artisan tinker
```

## 📚 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Reference](https://docs.docker.com/compose/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Nginx Configuration](https://nginx.org/en/docs/)
- [Traefik Documentation](https://doc.traefik.io/traefik/)

## 🤝 Support

For issues related to Docker deployment:

1. Check the logs: `./docker/deploy.sh logs`
2. Verify environment variables
3. Ensure all prerequisites are met
4. Check Docker and Docker Compose versions

---

**Happy Deploying! 🚀**
