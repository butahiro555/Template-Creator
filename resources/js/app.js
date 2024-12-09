import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';

const app = createApp(App);

// ページ読み込み時にダークモードの状態を取得
const darkModeState = localStorage.getItem('darkMode');
if (darkModeState === 'enabled') {
    document.body.classList.add('dark-mode');
}

app.mount('#app');