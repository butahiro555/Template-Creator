<h1>Template&nbsp;Creator</h1>
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
https://template-creator-0b1fb002af8e.herokuapp.com

<h3>メイン機能の使い方</h3>
<a href="https://gyazo.com/b9be552471658a84ada8bc3d3280083b"><img src="https://i.；gyazo.com/b9be552471658a84ada8bc3d3280083b.gif" alt="Image from Gyazo" width="968"/></a>

<h3>使用技術一覧</h3>
- 使用言語: PHP 8.3
<br>
- フレームワーク: Laravel 10
<br>
- CSSフレームワーク: Tailwind CSS
<br>
- 主要パッケージ: Axios, Font Awesome
<br>
- 環境構築ツール: Docker, Docker Compose
<br>
- 本プロジェクトのコンテナ構成は、php:8.3-fpm、nginx、mysql、Redisを使用しています。
<br>
詳細は以下のリポジトリにて確認可能です。
<br>
https://github.com/butahiro555/project_on_docker

<h3>環境ごとの設定</h3>
開発環境
- データベース: MySQLコンテナ
<br>
- メールサービス: Mailpitコンテナ (ローカルテスト用)
<br>
- APIキー: テスト用のキーを.envにて管理
<br>
- キャッシュ: Redisコンテナ
<br>
<br>
本番環境
- データベース: PostgreSQL (Heroku)
<br>
- メールサービス: Mailjet
<br>
- APIキー: 環境変数から取得
<br>
- キャッシュ: Redis

<h3>実装機能一覧</h3>
セッション、トークン、メール認証を利用した、ユーザー登録機能
<br>
セッション、トークン、メール認証を利用した、パスワードを忘れたユーザー向けのパスワード再設定機能
<br>
メール送信回数の制限機能
<br>
ログイン機能
<br>
ユーザー名、パスワード変更機能
<br>
退会機能
<br>
定型文の作成 / コピー / 更新 / 削除機能
<br>
定型文の検索機能
<br>
定型文のソート機能
<br>
ページネーション機能
<br>
<h3>Licence</h3>
"Laravel framework" is open source software licensed under <a href="https://en.wikipedia.org/wiki/MIT_License">the MIT license</a>.
<br>
<br>
「Laravel framework」は、<a href="https://en.wikipedia.org/wiki/MIT_License">MITライセンス</a>の下にライセンスされているオープンソースのソフトウェアです。
