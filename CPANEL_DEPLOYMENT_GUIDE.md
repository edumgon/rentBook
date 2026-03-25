# cPanel Deployment Guide for Book Lending Application

This guide will help you deploy your Laravel book lending application to cPanel hosting.

## 📋 Prerequisites

1. **cPanel Hosting Account** with:
   - PHP 8.2+ support
   - MySQL database
   - SSH access (recommended)
   - File Manager access

2. **Domain Name** pointed to your cPanel account

3. **Git Access** to your GitHub repository

## 🚀 Step-by-Step Deployment

### 1. Prepare Your Application

#### 1.1 Update Environment Configuration
Create a production `.env` file:

```bash
# Copy from example
cp .env.example .env
```

Or use the cPanel template:
```bash
# Copy from cPanel template
cp .env.cpanel .env
```

Edit `.env` file with production values:
```env
APP_NAME="Book Lending Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com/rentbook

# Database (will be set in cPanel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Generate app key
php artisan key:generate
```

#### 1.2 Optimize for Production
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Create production caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. cPanel Setup

#### 2.1 Create Database
1. Log into cPanel
2. Go to **MySQL Databases**
3. Create a new database:
   - Database name: `rentbook_db`
   - Username: `rentbook_user`
   - Password: Generate strong password
4. Add user to database with all privileges

#### 2.2 Create Subdomain (Optional)
1. Go to **Domains** → **Subdomains**
2. Create subdomain: `rentbook.yourdomain.com`
3. Document root: `/public_html/rentbook`

#### 2.3 Set PHP Version
1. Go to **MultiPHP Manager**
2. Set PHP version to **8.2** or higher
3. Enable required extensions:
   - bcmath, ctype, curl, dom, fileinfo
   - filter, gd, hash, json, mbstring
   - mysql, openssl, pdo, pdo_mysql
   - session, tokenizer, xml, zip

#### 2.4 Configure File Permissions
Via **File Manager** or SSH:
```bash
# Set proper permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 755 storage bootstrap/cache
chmod 600 .env
```

### 3. Upload Application Files

#### Method 1: Git Clone (Recommended)
1. Go to **Terminal** in cPanel or use SSH
2. Navigate to your document root:
   ```bash
   cd public_html
   git clone https://github.com/edumgon/rentBook.git rentbook
   cd rentbook
   ```

#### Method 2: File Manager Upload
1. Compress your project files (excluding node_modules, .git)
2. Upload via **File Manager**
3. Extract the archive
4. Rename folder to `rentbook`

### 4. Configure Application

#### 4.1 Update .env File
1. Edit `.env` file in cPanel File Manager
2. Set your database credentials:
   ```env
   DB_DATABASE=rentbook_db
   DB_USERNAME=rentbook_user
   DB_PASSWORD=your_actual_password
   APP_URL=https://yourdomain.com/rentbook
   ```

#### 4.2 Run Database Migrations
1. Go to **Terminal** or SSH
2. Navigate to your application:
   ```bash
   cd public_html/rentbook
   ```
3. Run migrations:
   ```bash
   php artisan migrate --force
   ```

#### 4.3 Seed Initial Data (Optional)
```bash
php artisan db:seed --class=DatabaseSeeder --force
```

### 5. Configure Web Server

#### 5.1 Update .htaccess
Create/edit `.htaccess` in your `rentbook` folder:

```apache
# Laravel .htaccess
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# PHP Settings
<IfModule mod_php7.c>
    php_value upload_max_filesize 64M
    php_value post_max_size 64M
    php_value memory_limit 256M
    php_value max_execution_time 300
</IfModule>

<IfModule mod_php8.c>
    php_value upload_max_filesize 64M
    php_value post_max_size 64M
    php_value memory_limit 256M
    php_value max_execution_time 300
</IfModule>
```

#### 5.2 Configure Document Root
Ensure your domain/subdomain points to the `public` folder:
- Document root: `/public_html/rentbook/public`

### 6. SSL Configuration

#### 6.1 Enable SSL
1. Go to **SSL/TLS** → **Let's Encrypt SSL**
2. Install SSL certificate for your domain
3. Enable **Force HTTPS Redirect**

#### 6.2 Update Laravel for HTTPS
Add to `AppServiceProvider.php`:
```php
use Illuminate\Support\Facades\URL;

public function boot()
{
    if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }
}
```

### 7. Test Your Application

#### 7.1 Basic Tests
1. Navigate to `https://yourdomain.com/rentbook`
2. Check if the application loads
3. Test login functionality
4. Verify database connectivity

#### 7.2 Create Test User
```bash
php artisan tinker
User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => Hash::make('password')]);
```

### 8. Ongoing Maintenance

#### 8.1 Set Up Cron Job
1. Go to **Cron Jobs** in cPanel
2. Add new cron job:
   - Command: `cd /home/username/public_html/rentbook && php artisan schedule:run >> /dev/null 2>&1`
   - Schedule: `* * * * *` (every minute)

#### 8.2 Regular Tasks
- Update dependencies: `composer update`
- Clear caches periodically
- Monitor error logs in `storage/logs`
- Backup database regularly

## 🔧 Troubleshooting

### Common Issues

#### 500 Internal Server Error
1. Check file permissions (755 for folders, 644 for files)
2. Verify `.env` file is correct
3. Check `storage/logs/laravel.log` for errors

#### Database Connection Error
1. Verify database credentials in `.env`
2. Ensure database user has proper privileges
3. Check if database exists

#### White Screen/Blank Page
1. Enable error reporting temporarily:
   ```php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
   ```

#### Images/Assets Not Loading
1. Check `public` folder permissions
2. Verify `.htaccess` configuration
3. Clear Laravel caches

### Performance Optimization

#### Enable OPcache
1. Go to **Select PHP Version** → **Extensions**
2. Enable `OPcache` extension

#### Configure Caching
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📞 Support Resources

### cPanel Documentation
- [cPanel User Guide](https://docs.cpanel.net/)
- [PHP Configuration](https://docs.cpanel.net/cpanel/multiphp-manager/)

### Laravel Documentation
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Laravel Optimization](https://laravel.com/docs/deployment#optimization)

### Getting Help
1. Check cPanel error logs
2. Review Laravel log files
3. Contact your hosting provider support

## 🎉 Success!

Your book lending application should now be live on cPanel! You can:
- Access it at your domain
- Manage books, borrowers, and loans
- Enjoy the full functionality of your application

Remember to regularly update and maintain your application for optimal performance and security!
