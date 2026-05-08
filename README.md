# NuxGameTest

## Installation Guide

Follow these steps to install and run the project locally.

### 1. Clone the repository

```bash
git clone https://github.com/Leserg41/nuxgametest.git
cd nuxgame-test
```

### 2. Copy the environment file

```bash
cp .env.example .env
```

### 3. Install PHP dependencies

```bash
composer install
```

### 4. Install Node dependencies

```bash
npm install
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Configure your database

Open `.env` and update the following values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 7. Run database migrations

```bash
php artisan migrate
```

### 8. Build frontend assets

```bash
npm run dev
```

### 9. Start the application

```bash
php artisan serve
```

Then open the URL shown in the terminal, typically:

```text
http://127.0.0.1:8000
```

## Notes

- Do not commit your `.env` file.
- If you are using a different database system, update the `.env` values accordingly.
- If you need to reset the database, run:

```bash
php artisan migrate:fresh
```
