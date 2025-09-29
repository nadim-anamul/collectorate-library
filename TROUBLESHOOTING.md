# 🐛 Troubleshooting Guide

## Container Restart Issues

### Problem: "Container is restarting, wait until the container is running"

This error occurs when Docker containers are in a restart loop. Here's how to diagnose and fix it:

### 1. Check Container Status
```bash
# Check all container status
docker compose ps

# Check specific container logs
docker compose logs app
docker compose logs mysql
docker compose logs redis
```

### 2. Common Causes & Solutions

#### A. Missing Environment File
**Problem**: `.env` file doesn't exist or has incorrect values
**Solution**:
```bash
# Copy example environment file
cp .env.example .env

# Edit with correct values
nano .env
```

#### B. Database Connection Issues
**Problem**: App container can't connect to MySQL
**Solution**:
```bash
# Check MySQL container logs
docker compose logs mysql

# Verify database is ready
docker compose exec mysql mysql -u root -proot_password -e "SELECT 1;"

# Restart MySQL if needed
docker compose restart mysql
```

#### C. Port Conflicts
**Problem**: Ports 8989, 3306, 6379, or 8080 are already in use
**Solution**:
```bash
# Check what's using the ports
sudo netstat -tulpn | grep :8989
sudo netstat -tulpn | grep :3306
sudo netstat -tulpn | grep :6379
sudo netstat -tulpn | grep :8080

# Kill processes using the ports or change ports in docker-compose.yml
```

#### D. Insufficient Resources
**Problem**: Not enough memory or disk space
**Solution**:
```bash
# Check system resources
free -h
df -h

# Clean up Docker resources
docker system prune -a
docker volume prune
```

### 3. Step-by-Step Recovery

#### Option 1: Clean Restart
```bash
# Stop all containers
docker compose down

# Remove volumes (WARNING: This will delete all data)
docker compose down -v

# Rebuild and start
docker compose build --no-cache
docker compose up -d

# Wait and check status
sleep 30
docker compose ps
```

#### Option 2: Gradual Startup
```bash
# Start services one by one
docker compose up -d mysql
sleep 20
docker compose up -d redis
sleep 10
docker compose up -d app
sleep 15
docker compose up -d phpmyadmin
```

#### Option 3: Debug Mode
```bash
# Start containers in foreground to see logs
docker compose up

# Or start specific service
docker compose up app
```

### 4. Health Check Commands

```bash
# Check if MySQL is ready
docker compose exec mysql mysqladmin ping -h localhost -u root -proot_password

# Check if Redis is ready
docker compose exec redis redis-cli ping

# Check if PHP is working in app container
docker compose exec app php --version

# Check if Laravel can connect to database
docker compose exec app php artisan tinker
# Then run: DB::connection()->getPdo();
```

### 5. Advanced Debugging

#### Check Container Resource Usage
```bash
# Monitor container resources
docker stats

# Check container details
docker inspect collectorate-library-app
```

#### Check Docker Logs
```bash
# View all logs
docker compose logs

# Follow logs in real-time
docker compose logs -f

# View logs for specific service
docker compose logs -f app
```

#### Check File Permissions
```bash
# Check if files are accessible
docker compose exec app ls -la /var/www/html

# Check storage permissions
docker compose exec app ls -la /var/www/html/storage
```

### 6. Common Error Messages & Solutions

#### "Error response from daemon: Container is restarting"
- **Cause**: Container is in a crash loop
- **Solution**: Check logs and fix the underlying issue

#### "Connection refused" or "Can't connect to MySQL"
- **Cause**: Database not ready or connection issues
- **Solution**: Wait for MySQL to be healthy, check credentials

#### "Permission denied" errors
- **Cause**: File permission issues
- **Solution**: Fix file ownership and permissions

#### "Port already in use"
- **Cause**: Another service using the same port
- **Solution**: Change ports or stop conflicting services

### 7. Prevention Tips

1. **Always check logs first**: `docker compose logs [service]`
2. **Use health checks**: Wait for services to be healthy
3. **Monitor resources**: Ensure sufficient memory and disk space
4. **Keep environment files updated**: Ensure `.env` has correct values
5. **Use proper startup order**: Start dependencies first

### 8. Emergency Recovery

If nothing else works:

```bash
# Nuclear option - clean everything
docker compose down -v --remove-orphans
docker system prune -a --volumes
docker compose build --no-cache
docker compose up -d

# Wait and check
sleep 60
docker compose ps
docker compose logs
```

### 9. Getting Help

If you're still having issues:

1. **Collect information**:
   ```bash
   docker compose ps
   docker compose logs > logs.txt
   docker system df
   ```

2. **Check system requirements**:
   - Docker version: `docker --version`
   - Docker Compose version: `docker compose version`
   - Available memory: `free -h`
   - Available disk space: `df -h`

3. **Share the information** with the development team or community

---

**Remember**: Most container restart issues are caused by configuration problems, resource constraints, or dependency issues. Check the logs first, then follow the systematic approach above.
