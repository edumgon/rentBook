# 🚀 cPanel Deployment Checklist

## ✅ Pre-Deployment Checklist

### 1. Application Preparation
- [ ] Update `.env` file for production (use `.env.cpanel` as template)
- [ ] Generate new `APP_KEY` (`php artisan key:generate`)
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Clear all caches (`php artisan cache:clear`)
- [ ] Optimize for production (`php artisan optimize`)

### 2. cPanel Setup
- [ ] Create MySQL database and user
- [ ] Set PHP version to 8.2+
- [ ] Enable required PHP extensions
- [ ] Create subdomain or set up domain
- [ ] Configure document root to `/public` folder

### 3. File Upload
- [ ] Upload application files to cPanel
- [ ] Set correct file permissions (755/644)
- [ ] Make `storage` and `bootstrap/cache` writable
- [ ] Upload `.env` file with production settings

### 4. Database Setup
- [ ] Import database schema (run migrations)
- [ ] Test database connection
- [ ] Seed initial data if needed

### 5. Final Configuration
- [ ] Configure `.htaccess` for Laravel
- [ ] Set up SSL certificate
- [ ] Configure cron job for Laravel scheduler
- [ ] Test all functionality

## 🔧 Quick Commands

### SSH/Terminal Commands
```bash
# Navigate to app
cd public_html/rentbook

# Install dependencies (if needed)
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod 600 .env
```

### Create Test User (if needed)
```bash
php artisan tinker
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('your-password')
]);
```

## 🌐 Access URLs

After deployment, your application will be available at:
- Main: `https://yourdomain.com/rentbook`
- Login: `https://yourdomain.com/rentbook/login`
- Dashboard: `https://yourdomain.com/rentbook/dashboard`

## 📞 Troubleshooting Quick Fix

### 500 Error?
1. Check file permissions
2. Verify `.env` file
3. Look at `storage/logs/laravel.log`

### Database Error?
1. Check database credentials in `.env`
2. Ensure database exists
3. Verify user permissions

### Blank Page?
1. Enable PHP error reporting temporarily
2. Check PHP version compatibility
3. Clear Laravel caches

## 🎉 Success Indicators

- [ ] Application loads without errors
- [ ] Login page works
- [ ] Can create books/borrowers/loans
- [ ] Database operations work
- [ ] SSL is active
- [ ] All links work correctly

**Ready to go live! 🚀**
