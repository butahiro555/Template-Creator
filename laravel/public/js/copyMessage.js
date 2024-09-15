function copyToClipboard(elementId) {
    var copyTarget = document.getElementById(elementId);
    if (navigator.clipboard) {
        navigator.clipboard.writeText(copyTarget.value).then(function() {
            alert('コピー完了！');
        }).catch(function(error) {
            console.error('コピーに失敗しました:', error);
        });
    } else {
        // 古いブラウザ用の処理
        copyTarget.select();
        document.execCommand('copy');
        alert('コピー完了！');
    }
}
