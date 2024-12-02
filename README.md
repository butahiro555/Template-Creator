<h1>Template&nbsp;Creator / 定型文管理サービス</h1>
<br>
こちらは、Template Creatorの開発環境のリポジトリとなります。
<br>
本番環境のリポジトリは以下のURLからご確認ください。
<br>
https://github.com/butahiro555/Template-Creator
<br>
<br>
<a href="https://gyazo.com/68b0398f3b4ff2851b50b6b283326d3f">
  <img src="https://i.gyazo.com/68b0398f3b4ff2851b50b6b283326d3f.png" alt="Image from Gyazo">
</a>

<h3>使用方法</h3>
1. $ git clone https://github.com/butahiro555/Template-Creator.git
<br>
2. $ cd Template-Creator
<br>
3. $ git checkout -b main-dev-env origin/main-dev-env
<br>
4. $ cp .env.example .env
<br>
5. $ cp .env.testing.example .env.testing
<br>
<br>
6. .envと、.env.testingのDB_*** と、MYSQL_***の環境変数を以下のように設定します。
<br>
DB_CONNECTION=mysql
<br>
DB_HOST=db
<br>
DB_PORT=3306
<br>
DB_DATABASE=任意のデータベース名
<br>
DB_USERNAME=任意のユーザー名
<br>
DB_PASSWORD=任意のパスワード名
<br>
<br>
MYSQL_ROOT_PASSWORD=任意のrootのパスワード名
<br>
MYSQL_DATABASE=任意のデータベース名
<br>
MYSQL_USER=任意のユーザー名
<br>
MYSQL_PASSWORD=任意のパスワード名
<br>
<br>
7. $ docker compose up -d
<br>
8. $ docker compose exec -it app bash
<br>
9. # chmod -R guo+w storage
<br>
10. # composer install
<br>
11. # php artisan key:generate
<br>
12. .envに出力したAPP_KEYを、.env.testingのAPP_KEYにもコピーします。
<br>
13. # php artisan storage:link
<br>
14. # php artisan config:clear
<br>
15. # php artisan migrate
<br>
16. # exit
<br>
17. $ docker compose exec -it db bash
<br>
18. # mysql -u root -p
<br>
19. # 設定したMYSQL_ROOT_PASSWORDを入力
<br>
20. mysql> CREATE DATABASE .env.testingで設定したデータベース名;
<br>
21. mysql> CREATE USER '.env.testingで設定したユーザー名'@'%' IDENTIFIED BY '.env.testingで設定したパスワード名';
<br>
22. mysql> GRANT ALL PRIVILEGES ON .env.testingで設定したデータベース名.* TO '.env.testingで設定したユーザー名'@'%';
<br>
23. mysql> FLUSH PRIVILEGES;
<br>
24. mysql> exit
<br>
25. # exit
<br>
26. $ docker compose exec -it app bash
<br>
27. # php artisan migrate --env=testing
<br>
28. # php artisan test --coverage
<br>
※ カバレッジレポートを出力する場合は次のコマンドを実行します。
<br>
# vendor/bin/phpunit --coverage-html storage/coverage
<br>
出力したカバレッジレポートは、storage/coverage直下にあるhtmlファイルをブラウザを使って確認できます。
<br>
29. # php artisan queue:work （手動テストする場合、キューシステムにデータベースを使用しているので、メール送信を実行可能にしておきます）
<br>
30. http://localhostにアクセスし、手動テストで各機能を確認します。
<br>
※ maipitコンテナが問題なく動作していれば、http://localhost:8025 でメールを確認できます。
<br>
