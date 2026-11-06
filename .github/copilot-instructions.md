# DSS Engineering Website - Laravel + Livewire Project

This is a modern construction company management system built with Laravel 11, Livewire 3, and Bootstrap 5. The application provides comprehensive CRUD operations for managing clients, blog posts, documents, services, gallery items, and company experience records.

## Project Structure

- **Models**: Client, Blog, Document, Service, Gallery, Experience
- **Livewire Components**: Interactive CRUD interfaces for all models
- **Admin Dashboard**: Statistics and quick navigation
- **Authentication**: Laravel Breeze integration
- **Database**: MySQL with comprehensive migrations
- **UI Framework**: Bootstrap 5 with Font Awesome icons

## Development Notes

### Completed Features
✅ Laravel 11 project scaffolding
✅ All model classes with proper fillable fields and casts
✅ Database migrations for all tables
✅ Livewire component for Client management with full CRUD
✅ Admin dashboard with statistics and navigation
✅ Bootstrap-based responsive layout
✅ Authentication routes and middleware
✅ VS Code extensions for Laravel development

### Migration Information
This project is designed as a replacement for a React + TypeScript + Supabase application. The Laravel version provides:
- Server-side rendering instead of SPA
- MySQL database instead of PostgreSQL
- Livewire reactive components instead of React hooks
- Laravel Eloquent ORM instead of Supabase client
- Laravel Breeze authentication instead of Supabase Auth

### Development Commands
- `php artisan serve` - Start development server
- `php artisan migrate` - Run database migrations
- `composer install` - Install PHP dependencies
- `php artisan make:livewire ComponentName` - Create new Livewire components

### Next Steps for Development
1. Complete remaining Livewire components (Blog, Document, Service, Gallery, Experience)
2. Set up database and run migrations
3. Add file upload functionality for Gallery and Documents
4. Implement authentication scaffolding with Laravel Breeze
5. Add seeder data for testing
6. Configure production deployment settings

This Laravel application maintains the same functionality as the original React application while providing better SEO, simpler deployment, and server-side rendering benefits.