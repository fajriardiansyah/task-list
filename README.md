# task-list

Aplikasi pengembangan untuk kepentingan operasional

---------------------------------------------------------------------

- Clone the repo

- Install Composer packages
      composer install

- Copy the environment file & edit it accordingly
      cp .env.example .env

- Generate application key
      php artisan key:generate

- Create Database then migrate and seed
      php artisan migrate --seed

- Linking Storage folder to public
      php artisan storage:link

- Serve the application
      php artisan serve
