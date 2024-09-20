1. $ git clone https://github.com/butahiro555/Template-Creator.git
2. $ cd Template-Creator
3. $ touch .env
4. Setting your parameter on .env
5. $ cd docker/mysql
6. $ touch my.cnf
7. Setting your parameter on my.cnf
8. $ cd ../..
9. $ docker compose up -d
10. $ docker compose exec -it app bash
11. $ chmod -R guo+w storage
12. composer install
13. $ php artisan storage:link
14. mv .env.example .env
15. Link the parameters mysql_parameter in docker_project/.env and DB_parameter in laravel/.env.
16. $ php artisan migrate
