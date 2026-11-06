# Environment Configuration for igeo.co.tz Development Environment

This document explains the environment configuration for the igeo.co.tz Laravel application in development.

## Environment File (.env)

The `.env` file contains all the environment-specific configuration for the application. The development configuration is as follows:

### Application Settings
- `APP_NAME="jgm Engineering Website"` - The name of the application
- `APP_ENV=local` - Sets the application to local development mode
- `APP_DEBUG=true` - Enables debug mode for development
- `APP_URL=http://localhost:8000` - The local development URL

### Database Configuration
- `DB_CONNECTION=mysql` - Uses MySQL database
- `DB_HOST=127.0.0.1` - Database host (localhost)
- `DB_PORT=3306` - MySQL default port
- `DB_DATABASE=jgmdb` - Development database name
- `DB_USERNAME=root` - Database user
- `DB_PASSWORD=mwansasu` - Database password

## Setup Instructions

1. **Make sure the .env file is properly configured:**
   Ensure the database credentials match your local MySQL setup

2. **Generate the application key:**
   ```bash
   php artisan key:generate
   ```

3. **Run database migrations:**
   ```bash
   php artisan migrate
   ```

4. **Install dependencies:**
   ```bash
   composer install
   npm install
   npm run build
   ```

## Resolved Issue

This configuration fixes the database connection error:
```
SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password: YES)
```

The error was caused by mismatched database credentials. The configuration now uses:
- Database name: `jgmdb`
- Username: `root`
- Password: `mwansasu`

## Starting the Development Server

To start the development server:

```bash
php artisan serve
```

The application will be accessible at http://127.0.0.1:8000 (or as configured in APP_URL)

## Common Commands

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Clear caches
php artisan config:clear
php artisan cache:clear
```