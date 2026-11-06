# 🔒 iGEO SSL Configuration Guide

## Production SSL Deployment Checklist

### 1. Pre-deployment Requirements
- [ ] Domain DNS properly configured
- [ ] Server has public IP address
- [ ] Apache/Nginx web server installed
- [ ] Port 80 and 443 open in firewall

### 2. SSL Certificate Installation

#### Option A: Let's Encrypt (Free - Recommended)
```bash
# Install Certbot
sudo apt update
sudo apt install certbot python3-certbot-apache

# Generate SSL certificate
sudo certbot --apache -d dssengineercoltd.co.tz -d www.dssengineercoltd.co.tz

# Test automatic renewal
sudo certbot renew --dry-run
```

#### Option B: Commercial SSL Certificate
1. Generate CSR (Certificate Signing Request)
2. Purchase SSL certificate from CA
3. Install certificate files on server
4. Configure web server

### 3. Laravel Environment Configuration

Update `.env` file in production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://dssengineercoltd.co.tz

# Force HTTPS
FORCE_HTTPS=true

# Session security
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=dssengineercoltd.co.tz
```

### 4. Web Server Configuration

#### Apache Configuration
```apache
<VirtualHost *:443>
    ServerName dssengineercoltd.co.tz
    DocumentRoot /var/www/dss-engineering/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "DENY"
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 443 ssl http2;
    server_name dssengineercoltd.co.tz;
    root /var/www/dss-engineering/public;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "DENY" always;
}
```

### 5. Security Testing

After deployment, test your SSL configuration:

1. **SSL Labs Test**: https://www.ssllabs.com/ssltest/
2. **Security Headers**: https://securityheaders.com/
3. **Mixed Content Check**: Browser developer tools

### 6. Monitoring and Maintenance

#### SSL Certificate Expiration Monitoring
```bash
# Check certificate expiration
openssl x509 -in /etc/letsencrypt/live/dssengineercoltd.co.tz/cert.pem -noout -dates

# Automatic renewal (Let's Encrypt)
echo "0 0,12 * * * root certbot renew" | sudo tee -a /etc/crontab > /dev/null
```

#### Health Check Script
Create a monitoring script to check SSL status:
```bash
#!/bin/bash
curl -I https://dssengineercoltd.co.tz/ | grep -E "(HTTP|SSL|TLS)"
```

### 7. Performance Optimization

#### Enable HTTP/2
```apache
# Apache
LoadModule http2_module modules/mod_http2.so
Protocols h2 http/1.1
```

#### GZIP Compression
```apache
LoadModule deflate_module modules/mod_deflate.so
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
```

### 8. Common Issues and Solutions

#### Issue: Mixed Content Warnings
**Solution**: Update all internal links to use HTTPS or relative URLs

#### Issue: Redirect Loops
**Solution**: Check proxy configuration and Laravel's `TrustProxies` middleware

#### Issue: Session Issues
**Solution**: Ensure `SESSION_SECURE_COOKIE=true` and proper session domain

### 9. Backup and Recovery

#### SSL Certificate Backup
```bash
# Backup Let's Encrypt certificates
sudo cp -r /etc/letsencrypt /backup/letsencrypt-backup-$(date +%Y%m%d)

# Backup custom certificates
sudo cp /path/to/ssl/certificates /backup/ssl-backup-$(date +%Y%m%d)
```

#### Recovery Process
1. Restore certificate files
2. Update web server configuration
3. Restart web server
4. Test SSL functionality

### 10. Security Best Practices

- [ ] Use strong SSL/TLS configuration (TLS 1.2+)
- [ ] Implement HSTS (HTTP Strict Transport Security)
- [ ] Enable security headers (CSP, X-Frame-Options, etc.)
- [ ] Regular security audits and updates
- [ ] Monitor SSL certificate expiration
- [ ] Use secure cipher suites
- [ ] Disable weak protocols (SSL 2.0/3.0, TLS 1.0/1.1)

### Contact Information
**DSS Engineering Co. Ltd**
- Website: https://dssengineercoltd.co.tz
- Email: dssengineercoltd@gmail.com
- Phone: +255 xxx xxx xxx
