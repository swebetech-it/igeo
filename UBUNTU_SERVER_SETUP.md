# Ubuntu Server Configuration for igeo.co.tz

This guide provides comprehensive instructions for setting up an Ubuntu server with the igeo.co.tz domain for your Laravel application.

## Prerequisites

- Ubuntu 20.04 LTS or 22.04 LTS server
- Domain name: igeo.co.tz pointing to your server's IP address
- Root or sudo access to the server

## Step 1: Update the System

```bash
sudo apt update && sudo apt upgrade -y
```

## Step 2: Install Apache Web Server

```bash
sudo apt install apache2 -y
sudo systemctl start apache2
sudo systemctl enable apache2
```

## Step 3: Install PHP and Required Extensions

```bash
# Add PHP repository
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.3 and extensions
sudo apt install php8.3 php8.3-cli php8.3-common php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath php8.3-json php8.3-ldap php8.3-intl php8.3-readline php8.3-fpm -y

# Enable Apache PHP module
sudo a2enmod php8.3
```

## Step 4: Install and Configure MySQL

```bash
# Install MySQL
sudo apt install mysql-server -y
sudo systemctl start mysql
sudo systemctl enable mysql

# Secure MySQL installation
sudo mysql_secure_installation
```

## Step 5: Install Additional Tools

```bash
# Install Git, Composer, and Node.js
sudo apt install git curl wget unzip -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js (LTS 20) and npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

## Step 6: Configure Apache for igeo.co.tz

1. Create the document root directory:
```bash
sudo mkdir -p /var/www/html/igeo
sudo chown -R $USER:$USER /var/www/html/igeo
sudo chmod -R 755 /var/www/html/igeo
```

2. Deploy the Application Code:

   **Option A: Uploading a Zip File (Recommended if developing on Windows)**
   
   Assuming you have uploaded `igeo.zip` to your user's home directory (e.g., using SCP or FileZilla):

   ```bash
   # Extract the zip file to the web root
   sudo unzip igeo.zip -d /var/www/html/igeo
   
   # Move files if they are inside a subfolder (common with some zippers)
   # Check structure: ls -la /var/www/html/igeo
   # If you see a single folder like 'igeo', move contents out:
   # sudo mv /var/www/html/igeo/igeo/* /var/www/html/igeo/
   # sudo mv /var/www/html/igeo/igeo/.* /var/www/html/igeo/ 2>/dev/null
   # sudo rmdir /var/www/html/igeo/igeo
   ```

   **Option B: Using Git**
   
   ```bash
   sudo git clone https://github.com/yourusername/igeo.git /var/www/html/igeo
   ```

3. Copy the virtual host configuration file to Apache:
```bash
sudo cp apache-vhost-igeo.conf /etc/apache2/sites-available/igeo.conf
```

4. Enable required Apache modules:
```bash
sudo a2enmod rewrite
sudo a2enmod ssl
sudo a2enmod headers
sudo a2enmod expires
sudo a2enmod deflate
sudo a2enmod setenvif
```

5. Enable the site:
```bash
sudo a2ensite igeo.conf
sudo systemctl reload apache2
```

## Step 7: Configure Laravel Application

1. Set up the Laravel environment:
```bash
cd /var/www/html/igeo
cp .env.example .env
```

2. Edit the .env file with your database settings:
```bash
nano .env
```

Essential settings for your domain:
```
APP_NAME=Igeo
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://igeo.co.tz

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=igeo_db
DB_USERNAME=igeo_user
DB_PASSWORD=your_database_password

FORCE_HTTPS=true
```

3. Generate the application key:
```bash
php artisan key:generate
```

4. Set proper permissions:
```bash
sudo chown -R www-data:www-data /var/www/html/igeo/storage
sudo chown -R www-data:www-data /var/www/html/igeo/bootstrap/cache
sudo chmod -R 775 /var/www/html/igeo/storage
sudo chmod -R 775 /var/www/html/igeo/bootstrap/cache
```

## Step 8: Install SSL Certificate with Let's Encrypt

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Obtain SSL certificate
sudo certbot --apache -d igeo.co.tz -d www.igeo.co.tz

# Test automatic renewal
sudo certbot renew --dry-run
```

## Step 9: Set up Database

1. Create database and user:
```bash
sudo mysql -u root -p
```

In MySQL:
```sql
CREATE DATABASE igeo_db;
CREATE USER 'igeo_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON igeo_db.* TO 'igeo_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

2. Run Laravel migrations:
```bash
cd /var/www/html/igeo
php artisan migrate --force
```

## Step 10: Configure Cron Jobs (for Laravel tasks)

```bash
crontab -e
```

Add this line:
```
* * * * * cd /var/www/html/igeo && php artisan schedule:run >> /dev/null 2>&1
```

## Step 11: Optimize Laravel Application

```bash
cd /var/www/html/igeo
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 12: Restart Services

```bash
sudo systemctl restart apache2
sudo systemctl restart php8.3-fpm
```

## Step 13: Final Checks

1. Check Apache status:
```bash
sudo systemctl status apache2
```

2. Check if the site is accessible:
```bash
curl -I https://igeo.co.tz
```

3. Verify SSL certificate:
```bash
sudo certbot certificates
```

## Troubleshooting

- If you encounter permission issues:
```bash
sudo chown -R www-data:www-data /var/www/html/igeo
```

- If Apache doesn't start:
```bash
sudo apache2ctl configtest
sudo journalctl -u apache2
```

- Check Laravel logs:
```bash
tail -f /var/www/html/igeo/storage/logs/laravel.log
```

## Security Recommendations

- Keep your system updated: `sudo apt update && sudo apt upgrade`
- Configure firewall: `sudo ufw enable`
- Regular SSL certificate renewal: `sudo certbot renew`
- Monitor logs regularly for suspicious activity
- Use strong passwords for database and admin access
- Regular backups of your application and database

Your Ubuntu server with the igeo.co.tz domain should now be properly configured and ready for your Laravel application!