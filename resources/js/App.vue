<template>
  <div>
    <button @click="toggleDarkMode" class="btn btn-dark">
      {{ state.darkMode ? 'ライトモード' : 'ダークモード' }}
    </button>
  </div>
</template>

<script setup>
import { inject } from 'vue';
import axios from 'axios';

// Vue.jsで提供された状態を受け取る
const state = inject('state');

// ダークモードの切り替え
const toggleDarkMode = async () => {
  try {
    const response = await axios.post('/theme/toggle');
    state.darkMode = response.data.darkMode === 'enabled';
  } catch (error) {
    console.error('Error toggling dark mode:', error);
  }
};
</script>