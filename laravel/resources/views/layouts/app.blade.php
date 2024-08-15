<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Template&nbsp;Creator</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <meta name="viewport" content="width=device-width,initial-scale=0.7">
    </head>
    <body>
        @include('commons.navbar')
        
        <div class="container">
            @include('commons.error_templates')
            
            @yield('content')
        </div>
        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
        
        <!-- クリップボードにコピーするためのjavascript -->
        <script>
        function copyToClipboard()
        {
            // コピー対象をJavaScript上で変数として定義する
            var copyTarget = document.getElementById("copyTarget");

            // コピー対象のテキストを選択する
            copyTarget.select();

            // 選択しているテキストをクリップボードにコピーする
            document.execCommand("Copy");

            // コピーをお知らせする
            alert("Copy Completed!" );
        }
        </script>
        
        <!-- deleteのときのメッセージ -->
        <script>
        $('#delete').click(function(){
                if(!confirm('Do you really want to delete this?')){
                    /* キャンセルの時の処理 */
                    return false;
                }
            });
        </script>
        
        <!-- updateのときのメッセージ -->
        <script>
        $('#warning').click(function(){
                if(!confirm('Do you really want to overwrite save this?')){
                    /* キャンセルの時の処理 */
                    return false;
                }
            });
        </script>
    </body>
</html>
