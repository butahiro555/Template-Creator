1. $ git clone https://github.com/butahiro555/Template-Creator.git
2. $ cd Template-Creator
3. mv .env.example .env
4. Setting your parameter on .env
5. $cd docker/mysql
6. $ mv my.cnf.example my.cnf
7. Setting your parameter on my.cnf
8. $ cd ../..
9. $ docker compose up -d
10. $ docker compose exec -it app bash
11. chown -R www-data:www-data /var/www/html/storage
12. composer install
13. $ php artisan storage:link
14. mv .env.example .env
15. php artisan key:generate
16. Link the parameters mysql_parameter in docker_project/.env and DB_parameter in laravel/.env.
17. $ php artisan migrate