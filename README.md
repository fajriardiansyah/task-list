# task-list

Aplikasi pengembangan untuk kepentingan operasional

---------------------------------------------------------------------

1. Clone the repo

2. Install Composer packages
composer install

3. Copy the environment file & edit it accordingly
cp .env.example .env

4. Generate application key
php artisan key:generate

5. Create Database then migrate and seed
php artisan migrate --seed

6. Linking Storage folder to public
php artisan storage:link

7. Serve the application
php artisan serve
