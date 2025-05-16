# Money Pro Management System

A comprehensive financial management system built with Laravel that helps users track income, expenses, budgets, and financial goals.

## System Requirements

- PHP >= 7.4
- Laravel >= 8.0
- SQLite
- Apache with mod_rewrite enabled

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd duit
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in .env:
```
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

## Base URL Configuration

The system is configured to run under the `/duit` base URL. This means:

- Access the application at: `http://yourdomain/duit`
- Login page: `http://yourdomain/duit/login`
- Dashboard: `http://yourdomain/duit/home`

### Key Files for Base URL Configuration:

1. `.htaccess` files:
   - Root .htaccess: Handles main URL routing
   - Public .htaccess: Manages assets and public files

2. Route configuration:
   - All routes are prefixed with '/duit'
   - RouteServiceProvider enforces base URL

3. Asset handling:
   - All assets use absolute URLs
   - CSS/JS files load from correct paths

## Features

1. User Authentication
   - Secure login system
   - Role-based access control
   - User profile management

2. Financial Management
   - Income tracking
   - Expense management
   - Budget planning
   - Financial goals
   - Account management

3. Reporting
   - Income vs Expense reports
   - Category-wise analysis
   - Budget tracking
   - Goal progress

4. Dashboard
   - Overview of finances
   - Quick access to key features
   - Visual data representation

## Directory Structure

```
duit/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   └── Providers/
├── config/
├── database/
├── public/
│   ├── css/
│   └── js/
├── resources/
│   └── views/
└── routes/
```

## Security

- CSRF protection enabled
- Secure session handling
- Protected sensitive routes
- Input validation
- XSS prevention

## Development

1. Run development server:
```bash
php artisan serve
```

2. Watch for asset changes:
```bash
npm run watch
```

## Production Deployment

1. Set production environment:
```bash
APP_ENV=production
APP_DEBUG=false
```

2. Optimize application:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Configure web server:
   - Set document root to public directory
   - Enable URL rewriting
   - Configure proper permissions

## License

[License details here]

## Support

For support, please contact [contact information]
