$(document).ready(function() {
    $('.delete-btn').click(function() {
        if (!confirm('本当に削除しますか？')) {
            return false;
        }
    });

    $('.update-btn').click(function() {
        if (!confirm('本当に上書き保存しますか？')) {
            return false;
        }
    });
});
