<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>パスワードリセット</title>
</head>
<body>
    <p>あなたの認証コードは次の通りです</p>
    <h2>{{ $verificationCode }}</h2>

    <p>このコードを確認ページに入力して、登録を完了してください。</p>

    <p>
        <a href="{{ $verificationUrl }}">このリンクをクリックして、パスワードリセットを完了してください。</a>
    </p>
    <p>※認証コードは、5分間のみ有効です。
    <p>本メールの内容に心当たりがない場合は、メールを削除してください。</p>
</body>
</html>
