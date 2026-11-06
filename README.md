# iGEO Website - Laravel + Livewire

A modern construction company website built with Laravel 11, Livewire, and Bootstrap. This project is a complete conversion from the original React + TypeScript + Supabase application to a full-stack PHP Laravel application.

## Features

- **Admin Dashboard**: Complete admin panel with statistics and quick access
- **Client Management**: CRUD operations for construction clients
- **Blog Management**: Create and manage blog posts
- **Document Management**: Upload and organize company documents
- **Service Management**: Manage construction services offered
- **Gallery Management**: Project photo gallery with categories
- **Experience Management**: Company milestones and achievements
- **Authentication**: Secure admin access with Laravel Breeze
- **Responsive Design**: Bootstrap-powered responsive interface

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Livewire 3 + Bootstrap 5
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Icons**: Font Awesome 6
- **PHP Version**: 8.1+
- **Security**: SSL/HTTPS with comprehensive security headers
- **Deployment**: Production-ready with SSL middleware

## About Laravelcenter"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Installation & Setup

### 1. Clone the Repository
```bash
git clone <repository-url>
cd dss-engineering
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 5. Start Development Server
```bash
php artisan serve
```

## Production Deployment

### SSL Configuration
This project includes comprehensive SSL/HTTPS configuration for production deployment:

1. **SSL Middleware**: Automatic HTTPS redirect and security headers
2. **Security Headers**: HSTS, CSP, X-Frame-Options, and more
3. **Deployment Script**: Automated SSL certificate installation
4. **Monitoring Tools**: SSL certificate and security checking

### Quick SSL Deployment
```bash
# Make deployment script executable
chmod +x deploy-ssl.sh

# Run deployment with SSL configuration
./deploy-ssl.sh
```

### SSL Monitoring
```bash
# Check SSL certificate status
php ssl-checker.php

# Check specific domain
php ssl-checker.php yourdomain.com
```

### Environment Configuration for Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dssengineercoltd.co.tz
FORCE_HTTPS=true
SESSION_SECURE_COOKIE=true
```

## Project Structure

- `app/Livewire/` - Livewire components for interactive UI
- `app/Models/` - Eloquent models for database entities
- `app/Http/Middleware/` - Custom middleware including SSL security
- `resources/views/livewire/` - Livewire component templates
- `database/migrations/` - Database schema migrations
- `deploy-ssl.sh` - SSL deployment automation script
- `ssl-checker.php` - SSL monitoring and validation tool
- `SSL-DEPLOYMENT-GUIDE.md` - Comprehensive SSL setup guide

## Security Features

- **HTTPS Enforcement**: Automatic redirect from HTTP to HTTPS
- **Security Headers**: Comprehensive security header implementation
- **SSL Certificate Management**: Automated Let's Encrypt integration
- **CSRF Protection**: Laravel's built-in CSRF protection
- **Authentication**: Secure admin authentication with Laravel Breeze
- **Input Validation**: Form validation and sanitization

## Company Information

**iGEO Pharmacy & Consulting**
- Website: https://dssengineercoltd.co.tz
- Email: dssengineercoltd@gmail.com
- Specialization: Modern Construction Solutions

## Learning Laravel

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
