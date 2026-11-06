# Environment Configuration for igeo.co.tz

This document explains the environment configuration for the igeo.co.tz Laravel application in production.

## Environment File (.env)

The `.env` file contains all the environment-specific configuration for the application. The production configuration for igeo.co.tz is as follows:

### Application Settings
- `APP_NAME="Igeo"` - The name of the application
- `APP_ENV=production` - Sets the application to production mode
- `APP_DEBUG=false` - Disables debug mode in production
- `APP_URL=https://igeo.co.tz` - The main application URL
- `FORCE_HTTPS=true` - Forces HTTPS connections

### Database Configuration
- `DB_CONNECTION=mysql` - Uses MySQL database
- `DB_HOST=127.0.0.1` - Database host (localhost)
- `DB_PORT=3306` - MySQL default port
- `DB_DATABASE=igeo_db` - Production database name
- `DB_USERNAME=igeo_user` - Database user
- `DB_PASSWORD=secure_password` - Database password (should be changed in production)

### Session Configuration
- `SESSION_DOMAIN=igeo.co.tz` - Sets the session domain for the website

## Setup Instructions

1. **Copy the .env file to your production server:**
   ```bash
   cp .env /var/www/html/igeo/
   ```

2. **Generate the application key:**
   ```bash
   cd /var/www/html/igeo
   php artisan key:generate
   ```

3. **Update database credentials:**
   Modify the database settings in the `.env` file with your actual database credentials.

4. **Set proper file permissions:**
   ```bash
   sudo chown www-data:www-data /var/www/html/igeo/.env
   sudo chmod 600 /var/www/html/igeo/.env
   ```

## Security Considerations

- Store the `.env` file outside the web root in production if possible
- Use strong, unique passwords for database and other services
- Regularly rotate passwords
- Never commit the `.env` file to version control
- Restrict file permissions to prevent unauthorized access

## Required Environment Variables

The following variables must be set correctly for the application to work properly:

- `APP_KEY` - Generated using `php artisan key:generate`
- `DB_DATABASE` - The database name
- `DB_USERNAME` - Database user with appropriate permissions
- `DB_PASSWORD` - Secure database password
- `APP_URL` - Must match your domain name

## Common Issues

1. **Application key not set**: Run `php artisan key:generate`
2. **Database connection errors**: Verify database credentials are correct
3. **HTTPS not working**: Ensure `FORCE_HTTPS=true` and SSL certificate is properly configured
4. **Session issues**: Verify `SESSION_DOMAIN` matches your domain

## Additional Configuration

For optimal performance in production, consider the following additional configurations:

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear caches when needed
php artisan config:clear
php artisan cache:clear
```