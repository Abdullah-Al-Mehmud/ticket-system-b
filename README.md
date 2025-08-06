
After cloning the Laravel project from Git, there are some important steps to follow to ensure the project works correctly on your local machine. Below are the detailed instructions:

---

## Initial Setup After Cloning 🚀

### 1. Install Dependencies (Composer)

Run Composer in the project root to install the required PHP dependencies:

```bash
composer install
```

If the `composer.lock` file is missing or you want to update all dependencies, use:

```bash
composer update
```

---

### 2. Environment File Setup (`.env`)

Laravel uses the `.env` file for configuration, and it is not committed to the repository because it contains sensitive data. Copy the example file and rename it:

```bash
cp .env.example .env
```

Then open `.env` and set your database connection details (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) as well as other environment variables such as `APP_URL`.

---

### 3. Generate Application Key

Laravel requires a unique application key for security. Generate and set the `APP_KEY` in your `.env` file:

```bash
php artisan key:generate
```

---

### 4. Database Migrations and Seeding

If your project includes migrations and seeders, run them to create the database schema and initial data:

```bash
php artisan migrate
php artisan db:seed # to seed dummy data
```

Or run migrations fresh and seed in one step:

```bash
php artisan migrate:fresh --seed
```

**Note:** `migrate:fresh --seed` will drop all tables, rebuild the schema, and then run the seeders.

---

### 5. Generate JWT Secret Key

If your project uses JWT authentication, generate the secret key:

```bash
php artisan jwt:secret
```

This will add a `JWT_SECRET` entry in your `.env` file. Without it, JWT tokens cannot be created or verified.

---

### 6. Create Storage Symlink

To make files in `storage/app/public` publicly accessible (such as uploaded images), create a symbolic link:

```bash
php artisan storage:link
```

This will create a `public/storage` link pointing to `storage/app/public`.

---

### 7. Run the Application

Once setup is complete, start the local development server:

```bash
php artisan serve
```

By default, it will be available at `http://127.0.0.1:8000` or `http://localhost:8000`. Open this URL in your browser to view the application.

---

## (OPTIONAL)
---

### 8. Install and Compile NPM Dependencies (if applicable)

If the project includes frontend assets managed by NPM or Yarn (e.g., Vue.js, React, Tailwind CSS), install and compile them:

```bash
npm install # or yarn install
npm run dev # for development
# or
npm run build # for production
```

For automatic recompilation during development:

```bash
npm run watch
```

---


## Publishing CORS Configuration

If your API needs CORS support and you are using the `fruitcake/laravel-cors` package, first install it:

```bash
composer require fruitcake/laravel-cors
```

Then publish the CORS configuration file:

```bash
php artisan vendor:publish --tag="cors"
```

This will create `config/cors.php`. In this file you can specify your frontend domains and other CORS settings:

```php
// config/cors.php

'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:3000', 'https://your-frontend-domain.com'],
```

Finally, clear the configuration cache:

```bash
php artisan config:clear
```

This will prepare your Laravel API for JWT-based authentication and proper CORS handling.
