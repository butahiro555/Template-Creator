$ git clone https://github.com/butahiro555/Template-Creator.git
$ cd Template-Creator
$ mv .env.example .env
Setting your parameter on .env
$ cd docker/mysql
$ mv my.cnf.example my.cnf
Setting your parameter on my.cnf
$ cd ../..
$ docker compose up -d
$ docker compose exec -it app bash
chown -R www-data:www-data /var/www/html/storage
composer install
$ php artisan storage:link
mv .env.example .env
php artisan key:generate
Link the parameters mysql_parameter in docker_project/.env and DB_parameter in laravel/.env.
$ php artisan migrate