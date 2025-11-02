Getting Started

Prerequisites

- PHP 8.2+
- Composer 2+
- Node.js 18+ and npm
- A database (MySQL/MariaDB or PostgreSQL)

Install

1. Copy environment file and set values
   - cp .env.example .env
   - php artisan key:generate
2. Install PHP dependencies
   - composer install
3. Install JS dependencies
   - npm install

Database

- Create a database and configure credentials in `.env`
- Run migrations and seeders
  - php artisan migrate --force
  - php artisan db:seed --force

Frontend assets

- Development: npm run dev
- Production build: npm run build

Running the app

- php artisan serve
- Visit http://localhost:8000

Admin panel

- Access at /admin (requires login). See modules.md for features and seeders for default roles and users.


