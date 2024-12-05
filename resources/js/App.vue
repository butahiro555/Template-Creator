<template>
  <div>
    <!-- ダークモード切り替えボタン -->
    <button @click="toggleDarkMode" class="p-2 bg-gray-200 dark:bg-gray-600 text-black dark:text-white rounded">
      Toggle Dark Mode
    </button>

    <!-- ダークモードとライトモードに対応したスタイル -->
    <div class="mt-4 bg-white dark:bg-gray-800 text-black dark:text-white p-4 rounded">
      <p>This is a text example that changes colors based on dark mode.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue';

// ダークモード状態を管理
const isDarkMode = ref(localStorage.getItem('darkMode') === 'enabled');

// ダークモードを切り替える関数
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  localStorage.setItem('darkMode', isDarkMode.value ? 'enabled' : 'disabled');
};

// クラスを状態に応じて反映
watchEffect(() => {
  const html = document.documentElement;
  if (isDarkMode.value) {
    html.classList.add('dark');
  } else {
    html.classList.remove('dark');
  }
});
</script>

<style>
/* 必要なら全体的なスタイルを調整 */
</style>