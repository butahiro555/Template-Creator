<h1>Template&nbsp;Creator / 定型文管理サービス</h1>

<a href="https://gyazo.com/68b0398f3b4ff2851b50b6b283326d3f">
  <img src="https://i.gyazo.com/68b0398f3b4ff2851b50b6b283326d3f.png" alt="Image from Gyazo">
</a>

これは定型文を作成、編集するためのアプリケーションです。
<br>
このアプリケーションを使うことで、とても簡単に定型文を管理することが出来ます。

<h3>サービス概要</h3>
私たちは日頃から仕事やプライベートで、メールのような文章を作成する機会によく遭遇します。
<br>
そんなとき、同じような表現を用いた文章を入力する手間や、メールの編集画面で作成途中の文章を誤送信してしまうミスを抑えることを想定して、このアプリケーションを作成しました。
<br>
また、タスクリストのようなアプリケーションとは違い、文章構成が分かりやすいUIや、少ないページ遷移の仕様にすることで、初めて使うユーザーでも使いやすいようなサービスになっています。

<h3>サービスのURL</h3>
https://www.template-creator.net

<h3>メイン機能の使い方</h3>
<a href="https://gyazo.com/4ec4be33bdfa21cffa02f8ea218ec830"><img src="https://i.gyazo.com/4ec4be33bdfa21cffa02f8ea218ec830.gif" alt="Image from Gyazo"></a>

<h3>使用技術一覧</h3>
- 使用言語：PHP 8.3
<br>
- フレームワーク：Laravel 10
<br>
- CSSフレームワーク：Bootstrap
<br>
- 主要パッケージ：Font Awesome
<br>
- 環境構築ツール：Docker、Docker Compose
<br>
- データベース：MySQL (開発環境)、PostgreSQL (本番環境)
<br>
- キューシステム：データベース、Redis
<br>
- メールサービス：Mailpit (開発環境)、Mailjet (本番環境)
<br>
- バージョン管理：Git、GitHub
<br>
- セキュリティ：HTTPS (本番環境)、CSRF対策、XSS対策
<br>
<br>
本プロジェクトのコンテナ構成は、php:8.3-fpm、nginx、mysql、Redisを使用しています。
<br>
詳細は以下のリポジトリにて確認可能です。
<br>
https://github.com/butahiro555/project_on_docker

<h3>アーキテクチャの概要</h3>
- トランザクション処理：ユーザー登録やパスワード再設定でデータ整合性を保つために、トランザクションを使用しています。
<br>
- 非同期処理：データベースをバックエンドにしたキューシステムで、迅速なページ遷移とデータ整合性を実現しました。
<br>
- データベースジョブ設定：トランザクション処理が成功した場合にのみ、メール送信ジョブが実行されるようにしました。
<br>
- メール送信制限：不正防止のため、メール再送信回数を3回に制限しています。
<br>
- ログ収集：エラーログを収集し、トラブルシューティングとセキュリティ監視を行います。
<br>
- 一時ユーザー削除：有効期限切れの一時ユーザーを定期的に自動削除します。
<h3>環境ごとの設定</h3>
開発環境
<br>
- OS：Windows
<br>
- ディストリビューション：Ubuntu 22.04.5 LTS
<br>
- データベース：MySQLコンテナ(Dockerイメージ:mysql:8.0.38)
<br>
- Webサーバー：Nginxコンテナ(Dockerイメージ:nginx:1.27)
<br>
- アプリケーションサーバー：PHPコンテナ(Dockerイメージ:php:8.3-fpm)
<br>
- メールサービス：Mailpitコンテナ(Dockerイメージ:mailpit)
<br>
- キューシステム：Redisコンテナ(Dockerイメージ:redis)
<br>
- APIキー：テスト用のキーを.envにて管理
<br>
<br>
本番環境
<br>
- データベース：PostgreSQL (Heroku)
<br>
- Webサーバー：Apache2 (Heroku)
<br>
- アプリケーションサーバー：PHP-FPM 8.3.12 (Heroku)
<br>
- メールサービス：Mailjet
<br>
- キューシステム：データベースを使用 (PostgreSQL)
<br>
- APIキー：Heroku環境変数から取得
<br>
<br>
※本アプリケーションは、以前AWS Cloud9にて作成したアプリケーションをローカルにてDocker環境を用いて改修を行い、Github、およびHerokuにアップロードし直しています。
<br>
以前作成したときのアプリケーションのコードは、以下のmasterブランチをご覧ください。
<br>
https://github.com/butahiro555/Template-Creator/tree/master

<h3>実装機能一覧</h3>
- セッション、トークン、メール認証を利用したユーザー登録機能
<br>
- パスワードを忘れたユーザー向けに、セッション、トークン、メール認証を利用したパスワード再設定機能
<br>
- 再送信をカウントするカラムや、セッションを利用したメール送信回数の制限機能
<br>
- メールアドレスとパスワードを利用したログイン機能
<br>
- ユーザー名変更機能
<br>
- パスワード変更機能
<br>
- ユーザー退会機能
<br>
- 定型文の作成、更新、削除、およびコピー機能
<br>
- 定型文のタイトルを入力することで、定型文を検索できる機能
<br>
- 作成日時および、更新日時で昇順、降順の並び替えが出来る定型文のソート機能
<br>
- ページネーション機能
<br>
- フォーム送信後にパスワードフィールドの値をクリアするDOM操作機能
<h3>Licence</h3>
"Laravel framework" is open source software licensed under <a href="https://en.wikipedia.org/wiki/MIT_License">the MIT license</a>.
<br>
<br>
「Laravel framework」は、<a href="https://en.wikipedia.org/wiki/MIT_License">MITライセンス</a>の下にライセンスされているオープンソースのソフトウェアです。
