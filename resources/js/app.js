import './bootstrap';
import { createApp, reactive, watchEffect } from 'vue';
import App from './App.vue';

// 初期値の取得
const appElement = document.getElementById('app');
const initialDarkMode = appElement.dataset.darkMode === 'enabled';

// 状態を管理
const state = reactive({
  darkMode: initialDarkMode,
});

// 状態に応じて`body`タグのクラスを更新
watchEffect(() => {
  if (state.darkMode) {
    document.body.classList.add('dark-mode');
  } else {
    document.body.classList.remove('dark-mode');
  }
});

// Vueアプリケーションのセットアップ
const app = createApp(App);
app.provide('state', state);
app.mount('#app');