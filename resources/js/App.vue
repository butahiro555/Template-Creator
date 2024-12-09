<template>
  <div>
    <!-- ダークモード切り替えボタン -->
    <button @click="toggleDarkMode" style="padding: 10px; background: gray; color: white; border: none; border-radius: 5px;">
      {{ isDarkMode ? "Light Mode" : "Dark Mode" }}
    </button>
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
  const html = document.body;
  if (isDarkMode.value) {
    html.classList.add('dark-mode');
  } else {
    html.classList.remove('dark-mode');
  }
});
</script>