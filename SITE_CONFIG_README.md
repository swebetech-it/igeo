# Ubuntu Server Configuration for igeo.co.tz

This repository contains the necessary configuration files and scripts to set up an Ubuntu server for hosting the igeo.co.tz Laravel application.

## Files Included

1. **apache-vhost-swebetech.conf** - Apache virtual host configuration for the igeo.co.tz domain
2. **UBUNTU_SERVER_SETUP.md** - Comprehensive step-by-step guide for Ubuntu server setup
3. **setup-swebetech.sh** - Automated script to help with the initial server setup

## Quick Start

### Using the Setup Script

1. Make the script executable:
   ```bash
   chmod +x setup-swebetech.sh
   ```

2. Run the script:
   ```bash
   ./setup-swebetech.sh
   ```

### Manual Setup

Follow the instructions in `UBUNTU_SERVER_SETUP.md` for a complete manual setup process.

## Domain Configuration

The Apache configuration includes:

- HTTP to HTTPS redirection
- WWW to non-WWW redirection
- Laravel-specific URL rewriting
- Security headers
- Performance optimizations
- SSL/TLS configuration
- File access restrictions

## Requirements

- Ubuntu 20.04 LTS or 22.04 LTS
- Domain name (igeo.co.tz) pointing to your server's IP
- Root or sudo access to the server

## Post-Installation Steps

After running the setup:

1. Upload your Laravel application files to `/var/www/html/swebetech`
2. Configure the `.env` file with your database credentials
3. Run database migrations: `php artisan migrate`
4. Generate application key: `php artisan key:generate`
5. Set up SSL certificate using Let's Encrypt

For detailed instructions, refer to the `UBUNTU_SERVER_SETUP.md` file.