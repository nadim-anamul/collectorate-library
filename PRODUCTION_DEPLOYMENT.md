# 🚀 Production Deployment Guide

## 📋 Pre-Deployment Checklist

### 1. Server Requirements
- **OS**: Ubuntu 20.04+ or CentOS 8+
- **RAM**: Minimum 2GB (4GB+ recommended)
- **Storage**: Minimum 20GB SSD
- **CPU**: 2+ cores
- **Docker**: 20.10+ with Docker Compose 2.0+

### 2. Domain & DNS Setup
- Purchase domain name
- Point domain A record to server IP
- Ensure DNS propagation is complete

### 3. Server Security
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Configure firewall
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

## 🔧 Environment Configuration

### 1. Create Production Environment File
```bash
# Copy example environment file
cp .env.example .env

# Edit with production values
nano .env
```

### 2. Required Environment Variables
```bash
# Application
APP_NAME="Collectorate Library"
APP_ENV=production
APP_KEY=base64:your_generated_app_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=collectorate_library_prod
DB_USERNAME=laravel_user
DB_PASSWORD=your_secure_database_password
DB_ROOT_PASSWORD=your_secure_root_password

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=your_secure_redis_password
REDIS_PORT=6379

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# SSL & Domain
DOMAIN=yourdomain.com
ACME_EMAIL=your@email.com

# File Storage
FILESYSTEM_DISK=local
# For S3 (optional)
# FILESYSTEM_DISK=s3
# AWS_ACCESS_KEY_ID=your_key
# AWS_SECRET_ACCESS_KEY=your_secret
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=your-bucket
```

### 3. Generate Application Key
```bash
# Generate secure app key
php artisan key:generate
```

## 🐳 Docker Production Deployment

### 1. Deploy Using Script
```bash
# Make deploy script executable
chmod +x docker/deploy.sh

# Deploy production environment
./docker/deploy.sh prod
```

### 2. Manual Docker Deployment
```bash
# Build and start production containers
docker compose -f docker-compose.prod.yml down --remove-orphans
docker compose -f docker-compose.prod.yml build --no-cache
docker compose -f docker-compose.prod.yml up -d

# Wait for services to be ready
sleep 15

# Run Laravel setup commands
docker compose -f docker-compose.prod.yml exec app php artisan key:generate --force
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker compose -f docker-compose.prod.yml exec app php artisan storage:link
docker compose -f docker-compose.prod.yml exec app php artisan optimize:production
```

## 📦 Application Optimization

### 1. Build Frontend Assets
```bash
# Install Node.js dependencies
docker compose -f docker-compose.prod.yml exec app npm ci

# Build production assets
docker compose -f docker-compose.prod.yml exec app npm run build
```

### 2. Laravel Optimization
```bash
# Run the custom optimization command
docker compose -f docker-compose.prod.yml exec app php artisan optimize:production

# Or run individual commands
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
docker compose -f docker-compose.prod.yml exec app php artisan event:cache
```

### 3. Database Seeding (Optional)
```bash
# Seed initial data
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --force

# Or seed specific data
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --class=RolesAndPermissionsSeeder
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --class=BulkBooksAndLoansSeeder
```

## 🔒 SSL Certificate Setup

### Automatic SSL with Let's Encrypt
The production setup includes Traefik with automatic SSL certificate generation:

1. **Traefik Configuration**: Already configured in `docker-compose.prod.yml`
2. **Certificate Storage**: Persistent volume for certificate storage
3. **Auto-renewal**: Certificates automatically renew before expiration

### Manual SSL Setup (Alternative)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Generate certificate
sudo certbot --nginx -d yourdomain.com

# Test renewal
sudo certbot renew --dry-run
```

## 📊 Monitoring & Maintenance

### 1. Health Checks
```bash
# Check container status
docker compose -f docker-compose.prod.yml ps

# Check application health
curl -I https://yourdomain.com/health

# Check logs
docker compose -f docker-compose.prod.yml logs -f app
```

### 2. Database Backups
```bash
# Create backup script
cat > backup-db.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
docker compose -f docker-compose.prod.yml exec mysql mysqldump -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} > backup_${DATE}.sql
echo "Backup created: backup_${DATE}.sql"
EOF

chmod +x backup-db.sh

# Schedule daily backups
echo "0 2 * * * /path/to/backup-db.sh" | crontab -
```

### 3. Log Rotation
```bash
# Configure logrotate for Docker logs
sudo nano /etc/logrotate.d/docker-containers
```

Add:
```
/var/lib/docker/containers/*/*.log {
    daily
    rotate 7
    compress
    size=1M
    missingok
    delaycompress
    copytruncate
}
```

## 🔧 Performance Optimization

### 1. Database Optimization
```sql
-- Add performance indexes
CREATE INDEX idx_books_title_en ON books(title_en);
CREATE INDEX idx_books_isbn ON books(isbn);
CREATE INDEX idx_books_category_id ON books(category_id);
CREATE INDEX idx_loans_user_id ON loans(user_id);
CREATE INDEX idx_loans_book_id ON loans(book_id);
CREATE INDEX idx_loans_status ON loans(status);
CREATE INDEX idx_loans_due_at ON loans(due_at);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_status ON users(status);
```

### 2. Redis Configuration
```bash
# Optimize Redis configuration
docker compose -f docker-compose.prod.yml exec redis redis-cli CONFIG SET maxmemory 256mb
docker compose -f docker-compose.prod.yml exec redis redis-cli CONFIG SET maxmemory-policy allkeys-lru
```

### 3. Application Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs

# Monitor resource usage
htop
iotop
```

## 🚨 Security Hardening

### 1. Application Security
```bash
# Set secure file permissions
docker compose -f docker-compose.prod.yml exec app chmod -R 755 storage
docker compose -f docker-compose.prod.yml exec app chmod -R 755 bootstrap/cache
docker compose -f docker-compose.prod.yml exec app chown -R appuser:appuser storage
docker compose -f docker-compose.prod.yml exec app chown -R appuser:appuser bootstrap/cache
```

### 2. Server Security
```bash
# Install fail2ban
sudo apt install fail2ban

# Configure fail2ban for SSH
sudo nano /etc/fail2ban/jail.local
```

Add:
```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[sshd]
enabled = true
port = ssh
logpath = /var/log/auth.log
maxretry = 3
```

### 3. Firewall Rules
```bash
# Configure UFW
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

## 🔄 Updates & Maintenance

### 1. Application Updates
```bash
# Pull latest changes
git pull origin main

# Rebuild and restart
./docker/deploy.sh prod

# Or manual update
docker compose -f docker-compose.prod.yml down
docker compose -f docker-compose.prod.yml build --no-cache
docker compose -f docker-compose.prod.yml up -d
```

### 2. Database Maintenance
```bash
# Optimize database
docker compose -f docker-compose.prod.yml exec mysql mysql -u root -p -e "OPTIMIZE TABLE books, loans, users;"

# Check database status
docker compose -f docker-compose.prod.yml exec mysql mysql -u root -p -e "SHOW PROCESSLIST;"
```

### 3. Cache Management
```bash
# Clear application cache
docker compose -f docker-compose.prod.yml exec app php artisan cache:clear

# Clear Redis cache
docker compose -f docker-compose.prod.yml exec redis redis-cli FLUSHALL
```

## 🐛 Troubleshooting

### Common Issues

1. **SSL Certificate Issues**
   ```bash
   # Check Traefik logs
   docker compose -f docker-compose.prod.yml logs traefik
   
   # Verify domain configuration
   nslookup yourdomain.com
   ```

2. **Database Connection Issues**
   ```bash
   # Check MySQL logs
   docker compose -f docker-compose.prod.yml logs mysql
   
   # Test database connection
   docker compose -f docker-compose.prod.yml exec app php artisan tinker
   ```

3. **Asset Loading Issues**
   ```bash
   # Rebuild assets
   docker compose -f docker-compose.prod.yml exec app npm run build
   
   # Check asset paths
   docker compose -f docker-compose.prod.yml exec app php artisan storage:link
   ```

4. **Permission Issues**
   ```bash
   # Fix file permissions
   docker compose -f docker-compose.prod.yml exec app chmod -R 755 storage
   docker compose -f docker-compose.prod.yml exec app chown -R appuser:appuser storage
   ```

### Debug Commands
```bash
# Check container status
docker compose -f docker-compose.prod.yml ps

# View detailed logs
docker compose -f docker-compose.prod.yml logs -f app
docker compose -f docker-compose.prod.yml logs -f mysql
docker compose -f docker-compose.prod.yml logs -f redis
docker compose -f docker-compose.prod.yml logs -f traefik

# Access application shell
docker compose -f docker-compose.prod.yml exec app bash

# Check PHP configuration
docker compose -f docker-compose.prod.yml exec app php -i

# Test database connection
docker compose -f docker-compose.prod.yml exec app php artisan tinker
```

## 📈 Performance Monitoring

### 1. Application Performance
```bash
# Monitor response times
curl -w "@curl-format.txt" -o /dev/null -s https://yourdomain.com

# Check Laravel logs
docker compose -f docker-compose.prod.yml exec app tail -f storage/logs/laravel.log
```

### 2. Resource Monitoring
```bash
# Monitor Docker resources
docker stats

# Monitor system resources
htop
iotop
```

### 3. Database Performance
```bash
# Check slow queries
docker compose -f docker-compose.prod.yml exec mysql mysql -u root -p -e "SHOW VARIABLES LIKE 'slow_query_log';"

# Monitor database connections
docker compose -f docker-compose.prod.yml exec mysql mysql -u root -p -e "SHOW PROCESSLIST;"
```

## 🎯 Post-Deployment Checklist

- [ ] Application accessible at https://yourdomain.com
- [ ] SSL certificate working properly
- [ ] Database migrations completed
- [ ] Assets loading correctly
- [ ] Email functionality working
- [ ] User registration/login working
- [ ] Book management features working
- [ ] Loan management features working
- [ ] Backup system configured
- [ ] Monitoring setup
- [ ] Security hardening completed
- [ ] Performance optimization applied

## 📞 Support & Maintenance

### Regular Maintenance Tasks
- **Daily**: Check application logs and health
- **Weekly**: Review security logs and updates
- **Monthly**: Database optimization and cleanup
- **Quarterly**: Security audit and dependency updates

### Emergency Procedures
1. **Application Down**: Check container status and logs
2. **Database Issues**: Restore from latest backup
3. **SSL Issues**: Check Traefik configuration and certificates
4. **Performance Issues**: Monitor resources and optimize

---

**🎉 Congratulations! Your Collectorate Library is now deployed in production!**

For additional support or questions, refer to the main documentation or contact the development team.
