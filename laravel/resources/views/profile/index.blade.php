<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .full-height {
            height: 90vh; /* 高さを少し調整 */
            display: flex;
            flex-direction: column;
            justify-content: center; /* 縦方向の中央揃え */
        }
        .content-wrapper {
            width: 100%; /* 幅を100%に設定 */
            max-width: 900px; /* 最大幅を設定 */
            margin: 0 auto; /* 左右中央に配置 */
            margin-bottom: 20px; /* 各セクションの下にマージンを追加 */
        }
    </style>
</head>
<body>
    <div class="d-flex flex-column align-items-center full-height">
        <div class="content-wrapper border p-3 mb-4 rounded">
            <h3 class="mb-4">User Profile</h3>
            @if (session('status'))
                <div class="text-center alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="d-flex align-items-center mb-3">
                <div class="flex-grow-1 text-start">
                    <strong>E-mail:</strong>
                </div>
                <div class="flex-grow-1 text-start">
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="flex-grow-1 text-start">
                    <strong>User Name:</strong>
                </div>
                <div class="flex-grow-1 text-start">
                    <p class="mb-0">{{ $user->name }}</p>
                </div>
                <div>
                    <a href="{{ route('profile.edit-name') }}" class="btn btn-success text-white">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="flex-grow-1 text-start">
                    <strong>Password:</strong>
                </div>
                <div class="flex-grow-1 text-start">
                    <p class="mb-0">********</p>
                </div>
                <div>
                    <a href="{{ route('profile.password-confirm') }}" class="btn btn-success text-white">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="content-wrapper border p-3 mb-4 rounded">
            <h3 class="mb-4">Option</h3>
            <div class="d-flex justify-content-end mb-4">
                <form action="{{ route('profile.delete-user-form') }}" method="GET" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary text-white">
                        <i class="bi bi-gear"></i>
                    </button>
                </form>
            </div>
            <div class="d-flex justify-content-end mb-4">
                <form action="{{ route('profile.delete-user-password-confirm-form') }}" method="GET" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger text-white">
                        <i class="fas fa-user-slash"></i> Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
    <!-- jQuery (必要) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap の JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>
