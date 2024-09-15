document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        // フォームが送信された後に少し遅延してパスワードフィールドをクリア
        setTimeout(function() {
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        }, 10); // 10ms遅延
    });
});
