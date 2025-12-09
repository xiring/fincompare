# Ubuntu 24.04 Deployment Guide

Complete deployment guide for FinCompare on Ubuntu 24.04 with Nginx, PHP 8.3-FPM, PostgreSQL, and Redis.

## Table of Contents

- [Prerequisites](#prerequisites)
- [System Setup](#system-setup)
- [Install PHP 8.3-FPM](#install-php-83-fpm)
- [Install PostgreSQL](#install-postgresql)
- [Install Redis](#install-redis)
- [Install Nginx](#install-nginx)
- [Install Node.js and pnpm](#install-nodejs-and-pnpm)
- [Install Composer](#install-composer)
- [Application Setup](#application-setup)
- [Configure Nginx](#configure-nginx)
- [Configure PHP-FPM](#configure-php-fpm)
- [Setup Supervisor for Horizon](#setup-supervisor-for-horizon)
- [Setup SSL with Let's Encrypt](#setup-ssl-with-lets-encrypt)
- [Firewall Configuration](#firewall-configuration)
- [Final Steps](#final-steps)
- [Maintenance Commands](#maintenance-commands)

## Prerequisites

- Ubuntu 24.04 server with root or sudo access
- Domain name pointing to your server's IP address (for SSL)
- At least 2GB RAM and 20GB disk space
- SSH access to the server

## System Setup

### 1. Update System Packages

```bash
sudo apt update
sudo apt upgrade -y
```

### 2. Create Application User

```bash
# Create a dedicated user for the application
sudo adduser --disabled-password --gecos "" fincompare
sudo usermod -aG sudo fincompare
```

### 3. Create Application Directory

```bash
sudo mkdir -p /var/www/html/fincompare
sudo chown fincompare:www-data /var/www/html/fincompare
```

## Install PHP 8.3-FPM

### 1. Add PHP Repository

```bash
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

### 2. Install PHP 8.3 and Required Extensions

```bash
sudo apt install -y php8.3-fpm \
    php8.3-cli \
    php8.3-common \
    php8.3-mysql \
    php8.3-pgsql \
    php8.3-zip \
    php8.3-gd \
    php8.3-mbstring \
    php8.3-curl \
    php8.3-xml \
    php8.3-bcmath \
    php8.3-redis \
    php8.3-intl \
    php8.3-readline \
    php8.3-tokenizer \
    php8.3-xml \
    php8.3-dom
```

### 3. Verify PHP Installation

```bash
php -v
# Should show PHP 8.3.x
```

## Install PostgreSQL

### 1. Install PostgreSQL

```bash
sudo apt install -y postgresql postgresql-contrib
```

### 2. Start and Enable PostgreSQL

```bash
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

### 3. Create Database and User

```bash
# Switch to postgres user
sudo -u postgres psql

# In PostgreSQL prompt, run:
CREATE DATABASE fincompare;
CREATE USER fincompare_user WITH ENCRYPTED PASSWORD 'your_secure_password_here';
GRANT ALL PRIVILEGES ON DATABASE fincompare TO fincompare_user;
ALTER DATABASE fincompare OWNER TO fincompare_user;
\q
```

### 4. Configure PostgreSQL for Remote Access (Optional)

If you need remote access, edit `/etc/postgresql/16/main/postgresql.conf`:

```bash
sudo nano /etc/postgresql/16/main/postgresql.conf
```

Uncomment and modify:
```
listen_addresses = 'localhost'  # or '*' for all interfaces
```

Edit `/etc/postgresql/16/main/pg_hba.conf`:
```bash
sudo nano /etc/postgresql/16/main/pg_hba.conf
```

Add:
```
host    fincompare    fincompare_user    127.0.0.1/32    md5
```

Restart PostgreSQL:
```bash
sudo systemctl restart postgresql
```

## Install Redis

### 1. Install Redis

```bash
sudo apt install -y redis-server
```

### 2. Configure Redis

Edit Redis configuration:
```bash
sudo nano /etc/redis/redis.conf
```

Set:
```
supervised systemd
maxmemory 256mb
maxmemory-policy allkeys-lru
```

### 3. Start and Enable Redis

```bash
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

### 4. Test Redis

```bash
redis-cli ping
# Should return: PONG
```

## Install Nginx

### 1. Install Nginx

```bash
sudo apt install -y nginx
```

### 2. Start and Enable Nginx

```bash
sudo systemctl start nginx
sudo systemctl enable nginx
```

### 3. Verify Nginx Installation

```bash
sudo systemctl status nginx
```

## Install Node.js and pnpm

### 1. Install Node.js 20.x (LTS)

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

### 2. Verify Node.js Installation

```bash
node -v
npm -v
```

### 3. Install pnpm

```bash
sudo npm install -g pnpm
```

### 4. Verify pnpm Installation

```bash
pnpm -v
```

## Install Composer

### 1. Download and Install Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### 2. Verify Composer Installation

```bash
composer --version
```

## Application Setup

### 1. Clone Repository

```bash
cd /var/www/html/fincompare
sudo -u fincompare git clone <your-repository-url> .
# Or upload files via SCP/SFTP
```

### 2. Install PHP Dependencies

```bash
cd /var/www/html/fincompare
sudo -u fincompare composer install --no-dev --optimize-autoloader --no-interaction
```

### 3. Install Node Dependencies

```bash
cd /var/www/html/fincompare
sudo -u fincompare pnpm install --frozen-lockfile
```

### 4. Build Frontend Assets

```bash
cd /var/www/html/fincompare
sudo -u fincompare pnpm run build
```

### 5. Configure Environment

```bash
cd /var/www/html/fincompare
sudo -u fincompare cp .env.example .env
sudo -u fincompare nano .env
```

Update the following in `.env`:

```env
APP_NAME=FinCompare
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=fincompare
DB_USERNAME=fincompare_user
DB_PASSWORD=your_secure_password_here

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
FILESYSTEM_DISK=public

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
REDIS_CACHE_DB=1

BROADCAST_DRIVER=log
MAIL_MAILER=smtp
# Configure your mail settings

SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

### 6. Generate Application Key

```bash
cd /var/www/html/fincompare
sudo -u fincompare php artisan key:generate
```

### 7. Run Migrations

```bash
cd /var/www/html/fincompare
sudo -u fincompare php artisan migrate --force
```

### 8. Seed Database (Optional, first deploy only)

```bash
cd /var/www/html/fincompare
sudo -u fincompare php artisan db:seed --force
```

### 9. Create Storage Link

```bash
cd /var/www/html/fincompare
sudo -u fincompare php artisan storage:link
```

### 10. Set Permissions

```bash
cd /var/www/html/fincompare
sudo chown -R fincompare:www-data /var/www/html/fincompare
sudo chmod -R 755 /var/www/html/fincompare
sudo chmod -R 775 /var/www/html/fincompare/storage
sudo chmod -R 775 /var/www/html/fincompare/bootstrap/cache
```

### 11. Optimize Application

```bash
cd /var/www/html/fincompare
sudo -u fincompare php artisan config:cache
sudo -u fincompare php artisan route:cache
sudo -u fincompare php artisan view:cache
```

## Configure Nginx

### 1. Create Nginx Configuration

```bash
sudo nano /etc/nginx/sites-available/fincompare
```

Add the following configuration (replace `yourdomain.com` with your actual domain):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/html/fincompare/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Deny access to hidden files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to sensitive files
    location ~ /\.(env|git|svn) {
        deny all;
        return 404;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Increase upload size limit
    client_max_body_size 20M;
}
```

### 2. Enable Site

```bash
sudo ln -s /etc/nginx/sites-available/fincompare /etc/nginx/sites-enabled/
```

### 3. Test Nginx Configuration

```bash
sudo nginx -t
```

### 4. Reload Nginx

```bash
sudo systemctl reload nginx
```

## Configure PHP-FPM

### 1. Edit PHP-FPM Pool Configuration

```bash
sudo nano /etc/php/8.3/fpm/pool.d/www.conf
```

Update the following values:

```ini
user = www-data
group = www-data
listen = /var/run/php/php8.3-fpm.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

### 2. Edit PHP Configuration

```bash
sudo nano /etc/php/8.3/fpm/php.ini
```

Update important settings:

```ini
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
max_input_time = 300
```

### 3. Restart PHP-FPM

```bash
sudo systemctl restart php8.3-fpm
```

## Setup Supervisor for Horizon

### 1. Install Supervisor

```bash
sudo apt install -y supervisor
```

### 2. Create Supervisor Configuration

```bash
sudo nano /etc/supervisor/conf.d/fincompare-horizon.conf
```

Add:

```ini
[program:fincompare_horizon]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/fincompare/artisan horizon
autostart=true
autorestart=true
redirect_stderr=true
user=www-data
stdout_logfile=/var/www/html/fincompare/storage/logs/horizon.log
stdout_logfile_maxbytes=10MB
logfile_backups=14
stopwaitsecs=3600
```

### 3. Update Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start fincompare_horizon:*
```

### 4. Verify Horizon Status

```bash
sudo supervisorctl status fincompare_horizon:*
```

## Setup SSL with Let's Encrypt

### 1. Install Certbot

```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 2. Obtain SSL Certificate

```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

Follow the prompts to configure SSL.

### 3. Auto-renewal Test

```bash
sudo certbot renew --dry-run
```

Certbot automatically sets up auto-renewal via cron.

## Firewall Configuration

### 1. Configure UFW Firewall

```bash
# Allow SSH
sudo ufw allow 22/tcp

# Allow HTTP
sudo ufw allow 80/tcp

# Allow HTTPS
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

## Final Steps

### 1. Setup Cron Job for Laravel Scheduler

```bash
sudo crontab -e -u www-data
```

Add:

```
* * * * * cd /var/www/html/fincompare && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Verify All Services

```bash
# Check Nginx
sudo systemctl status nginx

# Check PHP-FPM
sudo systemctl status php8.3-fpm

# Check PostgreSQL
sudo systemctl status postgresql

# Check Redis
sudo systemctl status redis-server

# Check Supervisor
sudo systemctl status supervisor
sudo supervisorctl status
```

### 3. Test Application

- Visit `http://yourdomain.com` (should redirect to HTTPS)
- Visit `https://yourdomain.com/admin` (admin panel)
- Visit `https://yourdomain.com/horizon` (Horizon dashboard, admin only)

## Maintenance Commands

### Application Updates

```bash
cd /var/www/html/fincompare

# Pull latest code
sudo -u fincompare git pull origin main

# Install/update dependencies
sudo -u fincompare composer install --no-dev --optimize-autoloader --no-interaction
sudo -u fincompare pnpm install --frozen-lockfile
sudo -u fincompare pnpm run build

# Run migrations
sudo -u fincompare php artisan migrate --force

# Clear and rebuild caches
sudo -u fincompare php artisan config:clear
sudo -u fincompare php artisan route:clear
sudo -u fincompare php artisan view:clear
sudo -u fincompare php artisan config:cache
sudo -u fincompare php artisan route:cache
sudo -u fincompare php artisan view:cache

# Restart Horizon
sudo supervisorctl restart fincompare_horizon:*

# Reload PHP-FPM
sudo systemctl reload php8.3-fpm
```

### Log Monitoring

```bash
# Laravel logs
tail -f /var/www/html/fincompare/storage/logs/laravel.log

# Horizon logs
tail -f /var/www/html/fincompare/storage/logs/horizon.log

# Nginx error logs
sudo tail -f /var/log/nginx/error.log

# PHP-FPM logs
sudo tail -f /var/log/php8.3-fpm.log
```

### Backup Database

```bash
# Create backup
sudo -u postgres pg_dump fincompare > /backup/fincompare_$(date +%Y%m%d_%H%M%S).sql

# Restore backup
sudo -u postgres psql fincompare < /backup/fincompare_backup.sql
```

### Troubleshooting

#### Check Service Status
```bash
sudo systemctl status nginx
sudo systemctl status php8.3-fpm
sudo systemctl status postgresql
sudo systemctl status redis-server
sudo supervisorctl status
```

#### Check Logs
```bash
# Application logs
tail -f /var/www/html/fincompare/storage/logs/laravel.log

# Nginx logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log

# PHP-FPM logs
sudo tail -f /var/log/php8.3-fpm.log
```

#### Restart Services
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.3-fpm
sudo systemctl restart postgresql
sudo systemctl restart redis-server
sudo supervisorctl restart fincompare_horizon:*
```

#### Check Permissions
```bash
sudo chown -R fincompare:www-data /var/www/html/fincompare
sudo chmod -R 755 /var/www/html/fincompare
sudo chmod -R 775 /var/www/html/fincompare/storage
sudo chmod -R 775 /var/www/html/fincompare/bootstrap/cache
```

## Security Recommendations

1. **Keep system updated**: `sudo apt update && sudo apt upgrade -y`
2. **Use strong passwords**: For database, Redis (if password-protected)
3. **Configure fail2ban**: Protect against brute force attacks
4. **Regular backups**: Automate database and file backups
5. **Monitor logs**: Set up log monitoring and alerts
6. **Firewall**: Only open necessary ports
7. **SSL/TLS**: Always use HTTPS in production
8. **Environment variables**: Never commit `.env` file
9. **File permissions**: Follow principle of least privilege
10. **Regular security audits**: Review and update dependencies

## Additional Resources

- [Laravel Deployment Documentation](https://laravel.com/docs/deployment)
- [Nginx Documentation](https://nginx.org/en/docs/)
- [PHP-FPM Configuration](https://www.php.net/manual/en/install.fpm.configuration.php)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [Redis Documentation](https://redis.io/documentation)
- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)

