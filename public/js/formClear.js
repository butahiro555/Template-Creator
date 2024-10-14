document.addEventListener('DOMContentLoaded', function() {
    // 全てのフォームを監視
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            // 少し遅延させてパスワードフィールドをクリア
            setTimeout(function() {
                // 全てのパスワードフィールドを取得し、値をクリア
                const passwordFields = form.querySelectorAll('input[type="password"]');
                
                passwordFields.forEach(function(field) {
                    field.value = '';
                });
            }, 10); // 10ms遅延
        });
    });
});
