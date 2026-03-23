# Book Lending Manager

A PHP-based personal book lending management system following SOLID principles, clean architecture, and test-driven development with multi-tenant user isolation.

## Features

- **Multi-tenant Architecture**: Each user's data is completely isolated
- **Social Authentication**: Login via Google, Facebook, or Microsoft
- **Book Management**: Add books manually or via Open Library API
- **Lending Tracking**: Track who has borrowed your books
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Secure**: Built with security best practices and SOLID principles

## Tech Stack

- **Backend**: PHP 8.2+ with Laravel 10
- **Database**: MySQL 8.0 with multi-tenant architecture
- **Frontend**: Blade templates + Tailwind CSS
- **Authentication**: Laravel Socialite
- **Testing**: PHPUnit
- **Development**: Docker Compose

## Quick Start

### Prerequisites

- Docker and Docker Compose installed
- Git

### Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd rentBook
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Start Docker containers:
```bash
docker-compose up -d
```

4. Install dependencies:
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

5. Generate application key:
```bash
docker-compose exec app php artisan key:generate
```

6. Run migrations:
```bash
docker-compose exec app php artisan migrate
```

7. Link storage:
```bash
docker-compose exec app php artisan storage:link
```

8. Build assets:
```bash
docker-compose exec app npm run build
```

### Access

- **Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Database**: localhost:3306

## Configuration

### Social Authentication

1. Create OAuth apps at:
   - Google: https://console.cloud.google.com/
   - Facebook: https://developers.facebook.com/
   - Microsoft: https://portal.azure.com/

2. Update `.env` file with your credentials:
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
```

## Development

### Running Tests

```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test
docker-compose exec app php artisan test --filter AuthenticationTest

# Generate coverage report
docker-compose exec app php artisan test --coverage
```

### Code Style

```bash
# Fix code style
docker-compose exec app ./vendor/bin/php-cs-fixer fix

# Run static analysis
docker-compose exec app ./vendor/bin/phpstan analyse
```

## Architecture

The application follows Clean Architecture principles:

- **Presentation Layer**: Controllers, Views, Routes
- **Application Layer**: Use Cases, Services  
- **Domain Layer**: Entities, Repositories Interfaces
- **Infrastructure Layer**: Database, External APIs

## Multi-Tenant Security

- Row-level security with `tenant_id` columns
- Global scopes in Laravel models for automatic filtering
- Middleware to set current tenant context
- Cross-tenant data access prevention

## Testing Strategy

- Unit Tests: Individual classes and methods
- Feature Tests: User workflows and scenarios
- Integration Tests: API integrations and database operations
- Minimum 80% code coverage required

## Deployment

### Development

Use Docker Compose for local development.

### Production (cPanel)

1. Push code to Git repository
2. Configure cPanel Git deployment
3. Set environment variables
4. Run `composer install --optimize-autoloader --no-dev`
5. Run `php artisan config:cache`
6. Run `php artisan migrate --force`

## Contributing

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Ensure all tests pass before submitting
4. Follow SOLID principles and clean architecture

## License

This project is open-source software licensed under the MIT license.
