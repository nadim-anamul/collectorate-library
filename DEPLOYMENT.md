# Production Deployment Guide

## Pre-deployment Checklist

### 1. Environment Configuration
- Copy `.env.example` to `.env` and configure production values
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Configure database credentials
- Set up Redis for caching and sessions
- Configure mail settings
- Set secure `APP_KEY`

### 2. Database Setup
```bash
# Run migrations
php artisan migrate --force

# Seed initial data (optional)
php artisan db:seed --force
```

### 3. Asset Compilation
```bash
# Install dependencies
npm ci

# Build production assets
npm run build:production

# Or use the optimized build
npm run build
```

### 4. Laravel Optimization
```bash
# Clear and cache configuration
php artisan config:cache

# Clear and cache routes
php artisan route:cache

# Clear and cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Clear application cache
php artisan cache:clear
```

### 5. File Permissions
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

## Server Configuration

### Nginx Configuration
```nginx
server {
    listen 80;
    listen 443 ssl http2;
    server_name your-domain.com;
    root /path/to/collectorate-library/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # SSL Configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    # Security headers
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### PHP-FPM Configuration
```ini
; Optimize PHP-FPM for production
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 1000
```

## Performance Optimizations

### 1. Enable OPcache
```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### 2. Redis Configuration
```bash
# Install Redis
sudo apt install redis-server

# Configure Redis
sudo nano /etc/redis/redis.conf
```

### 3. Database Optimization
```sql
-- Add indexes for better performance
CREATE INDEX idx_books_title_en ON books(title_en);
CREATE INDEX idx_books_isbn ON books(isbn);
CREATE INDEX idx_loans_user_id ON loans(user_id);
CREATE INDEX idx_loans_book_id ON loans(book_id);
CREATE INDEX idx_loans_status ON loans(status);
```

## Monitoring & Maintenance

### 1. Log Rotation
```bash
# Configure logrotate
sudo nano /etc/logrotate.d/laravel
```

### 2. Backup Strategy
```bash
# Database backup script
#!/bin/bash
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
```

### 3. Health Checks
- Set up monitoring for disk space, memory usage, and response times
- Configure alerts for error rates and performance degradation
- Monitor database performance and query execution times

## Security Considerations

### 1. SSL/TLS
- Use Let's Encrypt for free SSL certificates
- Enable HSTS headers
- Configure secure cipher suites

### 2. Application Security
- Regularly update dependencies
- Use strong passwords and API keys
- Implement rate limiting
- Enable CSRF protection
- Use secure session configuration

### 3. Server Security
- Keep the server updated
- Configure firewall rules
- Use fail2ban for intrusion prevention
- Regular security audits

## Troubleshooting

### Common Issues
1. **500 Internal Server Error**: Check file permissions and Laravel logs
2. **Asset Loading Issues**: Verify Vite build and asset paths
3. **Database Connection**: Verify database credentials and connectivity
4. **Cache Issues**: Clear all caches and restart services

### Useful Commands
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart redis
```
