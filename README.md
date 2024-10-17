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
https://template-creator-0b1fb002af8e.herokuapp.com

<h3>メイン機能の使い方</h3>


<h3>使用技術一覧</h3>
- 使用言語：PHP 8.3
<br>
- フレームワーク：Laravel 10
<br>
- CSSフレームワーク：Tailwind CSS
<br>
- 主要パッケージ：Axios, Font Awesome
<br>
- 環境構築ツール：Docker, Docker Compose
<br>
- 本プロジェクトのコンテナ構成は、php:8.3-fpm、nginx、mysql、Redisを使用しています。
<br>
詳細は以下のリポジトリにて確認可能です。
<br>
https://github.com/butahiro555/project_on_docker

<h3>アーキテクチャの概要</h3>
<br>
- トランザクション処理：ユーザー登録機能、およびパスワードを忘れたユーザー向けのパスワード再設定機能において、データベースの整合性を保つために、トランザクションを利用しています。
<br>
- 非同期処理：認証メール送信の際に、Redisを用いて非同期処理を導入し、ユーザー体験を損なわないよう設計しました。
<br>
- メール送信制限：不正行為の防止、およびリソースの保護のために、仮ユーザー登録、およびパスワード再設定時に同一メールアドレスに対して3回までの再送信制限回数を設けました。
<br>
- ログ収集機能：トラブルシューティング、およびセキュリティ監視のために、ユーザー登録時、パスワード再設定時にログを収集するように実装しました。

<h3>環境ごとの設定</h3>
開発環境
<br>
- データベース：MySQLコンテナ
<br>
- メールサービス：Mailpitコンテナ (ローカルテスト用)
<br>
- APIキー：テスト用のキーを.envにて管理
<br>
- キャッシュ：Redisコンテナ
<br>
<br>
本番環境
<br>
- データベース：PostgreSQL (Heroku)
<br>
- メールサービス：Mailjet
<br>
- APIキー：環境変数から取得
<br>
- キャッシュ：Redis
<br>
<br>
※本アプリケーションは、以前AWS Cloud9にて作成したアプリケーションをローカルにてDocker環境を用いて改修を行い、Github、およびHerokuにアップロードし直しています。
<br>
以前作成したときのアプリケーションのコードは、以下のmasterブランチをご覧ください。
<br>
https://github.com/butahiro555/Template-Creator/tree/master

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
ユーザー退会機能
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
